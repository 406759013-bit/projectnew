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

    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => [
                'manage_users',
                'manage_managers',
                'manage_staff',
                'manage_members',
                'view_reports',
                'approve_members',
                'system_settings'
            ],
            self::MANAGER => [
                'manage_staff',
                'view_reports',
                'view_members'
            ],
            self::STAFF => [
                'approve_members',
                'manage_members',
                'view_members'
            ],
            self::MEMBER => [
                'view_own_profile',
                'edit_own_profile'
            ],
        };
    }
}