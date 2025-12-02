<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'background_image',
        'border_color',
        'primary_color',
        'text_color',
        'logo_image',
        'logo_width',
        'logo_height',
        'admin_signature',
        'admin_signature_width',
        'admin_signature_height',
        'admin_name',
        'admin_position',
        'show_teacher_signature',
        'teacher_signature_position',
        'teacher_signature_width',
        'teacher_signature_height',
        'admin_signature_position',
        'is_active',
        'is_default',
        'created_by',
    ];

    protected $casts = [
        'show_teacher_signature' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'logo_width' => 'integer',
        'logo_height' => 'integer',
        'admin_signature_width' => 'integer',
        'admin_signature_height' => 'integer',
        'teacher_signature_width' => 'integer',
        'teacher_signature_height' => 'integer',
    ];

    /**
     * Template สร้างโดย Admin
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Certificates ที่ใช้ template นี้
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

    /**
     * ดึง URL รูปพื้นหลัง
     */
    public function getBackgroundImageUrlAttribute()
    {
        return $this->background_image ? asset('storage/' . $this->background_image) : null;
    }

    /**
     * ดึง URL โลโก้
     */
    public function getLogoImageUrlAttribute()
    {
        return $this->logo_image ? asset('storage/' . $this->logo_image) : null;
    }

    /**
     * ดึง URL ลายเซ็น Admin
     */
    public function getAdminSignatureUrlAttribute()
    {
        return $this->admin_signature ? asset('storage/' . $this->admin_signature) : null;
    }

    /**
     * ดึง template ที่ใช้งานอยู่
     */
    public static function getActiveTemplate()
    {
        $template = self::where('is_active', true)->first() 
            ?? self::where('is_default', true)->first();
        
        // ถ้าไม่มี template ให้สร้าง default
        if (!$template) {
            $template = self::createDefaultTemplate();
        }
        
        return $template;
    }

    /**
     * สร้าง Default Template
     */
    public static function createDefaultTemplate()
    {
        return self::create([
            'name' => 'Default Template',
            'description' => 'Template มาตรฐานสำหรับใบประกาศนียบัตร',
            'primary_color' => '#6366f1',
            'border_color' => '#d4af37',
            'text_color' => '#1f2937',
            'admin_name' => 'ผู้อำนวยการ',
            'admin_position' => 'ผู้รับรอง',
            'admin_signature_position' => 'right',
            'show_teacher_signature' => true,
            'teacher_signature_position' => 'left',
            'is_active' => true,
            'is_default' => true,
            'created_by' => 1,
        ]);
    }

    /**
     * ตั้งเป็น active template
     */
    public function setAsActive()
    {
        // ปิด active ของ template อื่นทั้งหมด
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        $this->update(['is_active' => true]);
    }
}
