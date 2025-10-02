<?php

namespace App\Enums;

enum UserStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'รอการอนุมัติ',
            self::APPROVED => 'อนุมัติแล้ว',
            self::REJECTED => 'ปฏิเสธ',
        };
    }
}