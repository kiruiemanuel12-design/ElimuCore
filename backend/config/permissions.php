<?php

// config/permissions.php
// Role-based permissions mapping for ElimuCore SMIS

return [
    'super_admin' => [
        'users.manage',
        'roles.manage',
        'settings.manage',
        'approvals.manage',
        'reports.view',
        'audit.view',
        'staff.manage',
        'students.manage',
        'attendance.manage',
        'fees.manage',
        'payroll.manage',
    ],

    'principal' => [
        'staff.manage',
        'students.approve',
        'staff.approve',
        'academics.manage',
        'attendance.view',
        'reports.view',
        'approvals.review',
        'students.view',
        'grades.view',
    ],

    'deputy_academic' => [
        'academics.manage',
        'attendance.record',
        'grades.manage',
        'reports.academic',
        'curriculum.manage',
        'students.view',
        'attendance.view',
    ],

    'deputy_admin' => [
        'operations.manage',
        'discipline.manage',
        'staff.view',
        'students.view',
        'reports.operational',
        'attendance.view',
    ],

    'teacher' => [
        'attendance.record',
        'grades.record',
        'students.view',
        'attendance.view',
    ],

    'bursar' => [
        'fees.manage',
        'payments.verify',
        'payroll.manage',
        'reports.financial',
        'audit.view',
        'staff.view',
    ],

    'student' => [
        'self.view',
        'grades.own',
        'attendance.own',
        'fees.own',
    ],

    'parent' => [
        'students.view_own_children',
        'grades.view',
        'attendance.view',
        'fees.view',
    ],
];
