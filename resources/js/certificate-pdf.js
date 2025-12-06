/**
 * Certificate PDF Generator using html2canvas + jsPDF
 * Captures HTML element and converts to PDF
 * Supports Thai fonts properly
 */

import { jsPDF } from 'jspdf';
import html2canvas from 'html2canvas';

/**
 * Generate PDF from HTML element using html2canvas
 * @param {HTMLElement} element - The HTML element to capture
 * @param {string} filename - Output filename
 * @param {string} action - 'download' or 'preview'
 */
export async function generatePDFFromElement(element, filename = 'certificate.pdf', action = 'download') {
    try {
        // A4 Landscape size (1123px x 794px)
        const captureWidth = 1123;
        const captureHeight = 794;
        
        // Capture the element with html2canvas
        const canvas = await html2canvas(element, {
            scale: 2, // 2x resolution for better quality
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            width: captureWidth,
            height: captureHeight,
        });

        // A4 Landscape dimensions in mm
        const pageWidth = 297;
        const pageHeight = 210;
        
        // Create PDF - A4 Landscape
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        // Add the canvas as image - FULL PAGE
        const imgData = canvas.toDataURL('image/png', 1.0);
        
        // Fill entire page
        pdf.addImage(imgData, 'PNG', 0, 0, pageWidth, pageHeight);

        // Output based on action
        if (action === 'download') {
            pdf.save(filename);
            return true;
        } else if (action === 'preview') {
            const blobUrl = pdf.output('bloburl');
            window.open(blobUrl, '_blank');
            return blobUrl;
        } else if (action === 'blob') {
            return pdf.output('blob');
        }

        return pdf;
    } catch (error) {
        console.error('PDF generation failed:', error);
        throw error;
    }
}

/**
 * Generate Certificate PDF from the certificate display on page
 * @param {string} certificateId - Certificate ID for filename
 * @param {string} action - 'download' or 'preview'
 */
export async function generateCertificatePDF(certificateId, action = 'download') {
    // Find the certificate container element
    const certElement = document.getElementById('certificate-container');
    
    if (!certElement) {
        throw new Error('Certificate container not found');
    }

    const filename = `certificate-${certificateId}.pdf`;
    return await generatePDFFromElement(certElement, filename, action);
}

/**
 * Download certificate as PDF
 * @param {string} certificateId - Certificate ID
 */
export async function downloadCertificate(certificateId) {
    try {
        await generateCertificatePDF(certificateId, 'download');
        return true;
    } catch (error) {
        console.error('Error downloading certificate:', error);
        throw error;
    }
}

/**
 * Preview certificate PDF in new window
 * @param {string} certificateId - Certificate ID
 */
export async function previewCertificate(certificateId) {
    try {
        await generateCertificatePDF(certificateId, 'preview');
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
    preview: previewCertificate,
    fromElement: generatePDFFromElement
};
