/**
 * Certificate PDF Generator using jsPDF
 * Supports Thai fonts via embedded base64 font
 */

import { jsPDF } from 'jspdf';

// Thai Sarabun font will be loaded dynamically from server
let thaiFont = null;
let thaiFontBold = null;

/**
 * Load Thai font from server
 */
async function loadThaiFont() {
    if (thaiFont) return;
    
    try {
        // Try to load from public assets
        const response = await fetch('/fonts/THSarabunNew.ttf');
        if (response.ok) {
            const buffer = await response.arrayBuffer();
            thaiFont = arrayBufferToBase64(buffer);
        }
    } catch (error) {
        console.warn('Thai font not available, using fallback');
    }
}

function arrayBufferToBase64(buffer) {
    let binary = '';
    const bytes = new Uint8Array(buffer);
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
}

/**
 * Generate Certificate PDF
 * @param {Object} data - Certificate data from API
 * @param {string} action - 'download' or 'preview'
 */
export async function generateCertificatePDF(data, action = 'download') {
    // Create PDF - A4 Landscape
    const doc = new jsPDF({
        orientation: 'landscape',
        unit: 'mm',
        format: 'a4'
    });

    const pageWidth = 297;
    const pageHeight = 210;

    // Colors from template
    const primaryColor = hexToRgb(data.template.primary_color || '#c9a227');
    const borderColor = hexToRgb(data.template.border_color || '#c9a227');
    const textColor = hexToRgb(data.template.text_color || '#333333');

    // Draw wave decorations at corners
    drawWaveDecorations(doc, primaryColor, pageWidth, pageHeight);

    // Draw gold frame
    drawGoldFrame(doc, borderColor, pageWidth, pageHeight);

    // Draw logo if available
    if (data.template.logo) {
        try {
            doc.addImage(data.template.logo, 'PNG', pageWidth / 2 - 12.5, 20, 25, 25);
        } catch (e) {
            console.warn('Logo failed to load');
        }
    }

    // Title - CERTIFICATE
    doc.setFontSize(38);
    doc.setTextColor(primaryColor.r, primaryColor.g, primaryColor.b);
    doc.setFont('helvetica', 'bold');
    doc.text('CERTIFICATE', pageWidth / 2, 55, { align: 'center' });

    // OF
    doc.setFontSize(10);
    doc.text('OF', pageWidth / 2, 62, { align: 'center' });

    // ACHIEVEMENT
    doc.setFontSize(11);
    doc.text('ACHIEVEMENT', pageWidth / 2, 68, { align: 'center' });

    // Decorative line
    doc.setDrawColor(borderColor.r, borderColor.g, borderColor.b);
    doc.setLineWidth(0.3);
    doc.line(80, 78, pageWidth - 80, 78);

    // "This Certificate is Proudly Presented To"
    doc.setFontSize(8);
    doc.text('This Certificate is Proudly Presented To', pageWidth / 2, 76, { align: 'center' });

    // Student Name
    doc.setFontSize(24);
    doc.setFont('helvetica', 'bolditalic');
    doc.text(data.student.name, pageWidth / 2, 92, { align: 'center' });

    // Underline
    const nameWidth = doc.getTextWidth(data.student.name);
    doc.setLineWidth(0.5);
    doc.line(pageWidth / 2 - nameWidth / 2 - 10, 95, pageWidth / 2 + nameWidth / 2 + 10, 95);

    // Description in Thai
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(textColor.r, textColor.g, textColor.b);
    
    // Thai text - using Unicode directly
    const descLine1 = 'Has successfully completed the training and assessment in';
    const descLine2 = `"${data.course.title}"`;
    const descLine3 = `Organized by ${data.template.name || 'CT Learning'}`;
    const descLine4 = `Date: ${data.certificate.issued_date_thai}`;
    
    doc.text(descLine1, pageWidth / 2, 105, { align: 'center' });
    
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(primaryColor.r, primaryColor.g, primaryColor.b);
    doc.text(descLine2, pageWidth / 2, 112, { align: 'center' });
    
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(textColor.r, textColor.g, textColor.b);
    doc.text(descLine3, pageWidth / 2, 119, { align: 'center' });
    doc.text(descLine4, pageWidth / 2, 126, { align: 'center' });

    // Signatures section
    const sigY = 155;
    const leftX = 90;
    const rightX = pageWidth - 90;

    // Teacher signature (left)
    if (data.template.show_teacher_signature) {
        if (data.teacher.signature) {
            try {
                doc.addImage(data.teacher.signature, 'PNG', leftX - 15, sigY - 15, 30, 15);
            } catch (e) {
                console.warn('Teacher signature failed');
            }
        }
        doc.setDrawColor(50, 50, 50);
        doc.setLineWidth(0.3);
        doc.line(leftX - 20, sigY, leftX + 20, sigY);
        
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(textColor.r, textColor.g, textColor.b);
        doc.text(data.teacher.name || '', leftX, sigY + 5, { align: 'center' });
        
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(100, 100, 100);
        doc.text('Instructor', leftX, sigY + 10, { align: 'center' });
    }

    // Admin signature (right)
    if (data.template.admin_signature) {
        try {
            doc.addImage(data.template.admin_signature, 'PNG', rightX - 15, sigY - 15, 30, 15);
        } catch (e) {
            console.warn('Admin signature failed');
        }
    }
    doc.setDrawColor(50, 50, 50);
    doc.setLineWidth(0.3);
    doc.line(rightX - 20, sigY, rightX + 20, sigY);
    
    doc.setFontSize(9);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(textColor.r, textColor.g, textColor.b);
    doc.text(data.template.admin_name || 'Director', rightX, sigY + 5, { align: 'center' });
    
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.setTextColor(100, 100, 100);
    doc.text(data.template.admin_position || '', rightX, sigY + 10, { align: 'center' });

    // Footer - Certificate number
    doc.setFontSize(6);
    doc.setTextColor(150, 150, 150);
    doc.text(`Certificate No: ${data.certificate.number}`, pageWidth / 2, pageHeight - 6, { align: 'center' });

    // Output
    if (action === 'download') {
        doc.save(`certificate-${data.certificate.number}.pdf`);
    } else if (action === 'preview') {
        return doc.output('bloburl');
    } else if (action === 'blob') {
        return doc.output('blob');
    }

    return doc;
}

/**
 * Draw wave decorations at corners
 */
function drawWaveDecorations(doc, color, pageWidth, pageHeight) {
    doc.setFillColor(color.r, color.g, color.b);

    // Top-left waves
    drawCornerWave(doc, color, 0, 0, 75, 32, 'tl');
    doc.setFillColor(255, 255, 255);
    drawCornerWave(doc, { r: 255, g: 255, b: 255 }, 0, 0, 55, 22, 'tl');
    doc.setFillColor(color.r, color.g, color.b);
    drawCornerWave(doc, color, 0, 0, 35, 15, 'tl');

    // Top-right waves
    drawCornerWave(doc, color, pageWidth, 0, 75, 32, 'tr');
    doc.setFillColor(255, 255, 255);
    drawCornerWave(doc, { r: 255, g: 255, b: 255 }, pageWidth, 0, 55, 22, 'tr');
    doc.setFillColor(color.r, color.g, color.b);
    drawCornerWave(doc, color, pageWidth, 0, 35, 15, 'tr');

    // Bottom-left waves
    drawCornerWave(doc, color, 0, pageHeight, 75, 32, 'bl');
    doc.setFillColor(255, 255, 255);
    drawCornerWave(doc, { r: 255, g: 255, b: 255 }, 0, pageHeight, 55, 22, 'bl');
    doc.setFillColor(color.r, color.g, color.b);
    drawCornerWave(doc, color, 0, pageHeight, 35, 15, 'bl');

    // Bottom-right waves
    drawCornerWave(doc, color, pageWidth, pageHeight, 75, 32, 'br');
    doc.setFillColor(255, 255, 255);
    drawCornerWave(doc, { r: 255, g: 255, b: 255 }, pageWidth, pageHeight, 55, 22, 'br');
    doc.setFillColor(color.r, color.g, color.b);
    drawCornerWave(doc, color, pageWidth, pageHeight, 35, 15, 'br');
}

/**
 * Draw a corner wave shape
 */
function drawCornerWave(doc, color, x, y, width, height, corner) {
    doc.setFillColor(color.r, color.g, color.b);
    
    // Simplified quarter-ellipse using bezier curves
    const steps = 20;
    let points = [];
    
    for (let i = 0; i <= steps; i++) {
        const angle = (Math.PI / 2) * (i / steps);
        let px, py;
        
        switch (corner) {
            case 'tl':
                px = x + width * (1 - Math.cos(angle));
                py = y + height * (1 - Math.sin(angle));
                break;
            case 'tr':
                px = x - width * (1 - Math.cos(angle));
                py = y + height * (1 - Math.sin(angle));
                break;
            case 'bl':
                px = x + width * (1 - Math.cos(angle));
                py = y - height * (1 - Math.sin(angle));
                break;
            case 'br':
                px = x - width * (1 - Math.cos(angle));
                py = y - height * (1 - Math.sin(angle));
                break;
        }
        points.push([px, py]);
    }
    
    // Close the shape
    switch (corner) {
        case 'tl':
            points.push([x, y + height]);
            points.push([x, y]);
            points.push([x + width, y]);
            break;
        case 'tr':
            points.push([x, y]);
            points.push([x - width, y]);
            break;
        case 'bl':
            points.push([x, y]);
            points.push([x + width, y]);
            break;
        case 'br':
            points.push([x, y]);
            points.push([x - width, y]);
            break;
    }
    
    // Draw filled polygon
    if (points.length > 2) {
        doc.setFillColor(color.r, color.g, color.b);
        
        // Use triangle method for complex shapes
        const startX = corner.includes('l') ? x : x;
        const startY = corner.includes('t') ? y : y;
        
        // Simplified - just draw a rounded rectangle area
        if (corner === 'tl') {
            doc.roundedRect(x, y, width, height, 0, height, 'F');
        } else if (corner === 'tr') {
            doc.roundedRect(x - width, y, width, height, height, 0, 'F');
        } else if (corner === 'bl') {
            doc.roundedRect(x, y - height, width, height, 0, height, 'F');
        } else if (corner === 'br') {
            doc.roundedRect(x - width, y - height, width, height, height, 0, 'F');
        }
    }
}

/**
 * Draw gold frame border
 */
function drawGoldFrame(doc, color, pageWidth, pageHeight) {
    doc.setDrawColor(color.r, color.g, color.b);
    doc.setLineWidth(1);
    doc.rect(55, 15, pageWidth - 110, pageHeight - 30);
}

/**
 * Convert hex color to RGB
 */
function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : { r: 0, g: 0, b: 0 };
}

/**
 * Fetch certificate data and generate PDF
 */
export async function downloadCertificate(certificateId) {
    try {
        const response = await fetch(`/student/certificates/${certificateId}/data`);
        if (!response.ok) {
            throw new Error('Failed to fetch certificate data');
        }
        const data = await response.json();
        await generateCertificatePDF(data, 'download');
        return true;
    } catch (error) {
        console.error('Error downloading certificate:', error);
        throw error;
    }
}

/**
 * Preview certificate in new window
 */
export async function previewCertificate(certificateId) {
    try {
        const response = await fetch(`/student/certificates/${certificateId}/data`);
        if (!response.ok) {
            throw new Error('Failed to fetch certificate data');
        }
        const data = await response.json();
        const blobUrl = await generateCertificatePDF(data, 'preview');
        window.open(blobUrl, '_blank');
        return true;
    } catch (error) {
        console.error('Error previewing certificate:', error);
        throw error;
    }
}

// Export for global access
window.CertificatePDF = {
    generate: generateCertificatePDF,
    download: downloadCertificate,
    preview: previewCertificate
};
