<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';
    case MEMBER = 'member';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'ผู้ดูแลระบบ',
            self::MANAGER => 'ผู้จัดการ',
            self::STAFF => 'เจ้าหน้าที่',
            self::MEMBER => 'สมาชิก',
        };
    }
}