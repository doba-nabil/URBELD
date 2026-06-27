<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            // Statistics & Home
            ['name' => 'home', 'guard_name' => 'admin', 'display_name' => ['en' => 'Show statistics', 'ar' => 'عرض الاحصائيات'], 'group' => 'statistics', 'group_name' => ['en' => 'Statistics', 'ar' => 'الاحصائيات']],
            ['name' => 'reports', 'guard_name' => 'admin', 'display_name' => ['en' => 'Reports', 'ar' => 'التقارير'], 'group' => 'reports', 'group_name' => ['en' => 'Reports', 'ar' => 'التقارير']],

            // Roles
            ['name' => 'roles.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Roles', 'ar' => 'عرض الأدوار'], 'group' => 'roles', 'group_name' => ['en' => 'Roles', 'ar' => 'الأدوار']],
            ['name' => 'roles.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Role', 'ar' => 'إضافة دور'], 'group' => 'roles', 'group_name' => ['en' => 'Roles', 'ar' => 'الأدوار']],
            ['name' => 'roles.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Role', 'ar' => 'تعديل دور'], 'group' => 'roles', 'group_name' => ['en' => 'Roles', 'ar' => 'الأدوار']],
            ['name' => 'roles.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Role', 'ar' => 'حذف دور'], 'group' => 'roles', 'group_name' => ['en' => 'Roles', 'ar' => 'الأدوار']],

            // Admins (Supervisors)
            ['name' => 'admins.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Admins', 'ar' => 'عرض المشرفين'], 'group' => 'admins', 'group_name' => ['en' => 'Admins', 'ar' => 'المشرفين']],
            ['name' => 'admins.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Admin', 'ar' => 'إضافة مشرف'], 'group' => 'admins', 'group_name' => ['en' => 'Admins', 'ar' => 'المشرفين']],
            ['name' => 'admins.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Admin', 'ar' => 'تعديل مشرف'], 'group' => 'admins', 'group_name' => ['en' => 'Admins', 'ar' => 'المشرفين']],
            ['name' => 'admins.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Admin', 'ar' => 'حذف مشرف'], 'group' => 'admins', 'group_name' => ['en' => 'Admins', 'ar' => 'المشرفين']],

            // Audits
            ['name' => 'audits.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Audits', 'ar' => 'عرض سجل العمليات'], 'group' => 'audits', 'group_name' => ['en' => 'Audits', 'ar' => 'سجل العمليات']],
            ['name' => 'audits.show', 'guard_name' => 'admin', 'display_name' => ['en' => 'Show Audit Details', 'ar' => 'تفاصيل سجل العمليات'], 'group' => 'audits', 'group_name' => ['en' => 'Audits', 'ar' => 'سجل العمليات']],

            // Users (Customers)
            ['name' => 'users.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Users', 'ar' => 'عرض المستخدمين'], 'group' => 'users', 'group_name' => ['en' => 'Users', 'ar' => 'المستخدمين']],
            ['name' => 'users.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create User', 'ar' => 'إضافة مستخدم'], 'group' => 'users', 'group_name' => ['en' => 'Users', 'ar' => 'المستخدمين']],
            ['name' => 'users.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit User', 'ar' => 'تعديل مستخدم'], 'group' => 'users', 'group_name' => ['en' => 'Users', 'ar' => 'المستخدمين']],
            ['name' => 'users.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete User', 'ar' => 'حذف مستخدم'], 'group' => 'users', 'group_name' => ['en' => 'Users', 'ar' => 'المستخدمين']],

            // Operations
            ['name' => 'contacts.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Contacts', 'ar' => 'عرض الاتصالات'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            ['name' => 'contacts.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Contact', 'ar' => 'حذف اتصال'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            
            ['name' => 'search-logs.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Search Logs', 'ar' => 'عرض سجل البحث'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            ['name' => 'search-logs.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Search Log', 'ar' => 'حذف سجل بحث'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            
            ['name' => 'notifications.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Notifications', 'ar' => 'عرض التنبيهات'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            ['name' => 'notifications.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Notification', 'ar' => 'حذف تنبيه'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            
            ['name' => 'user-membership-history.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Membership History', 'ar' => 'عرض تاريخ العضويات'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            
            ['name' => 'chats.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Chats', 'ar' => 'عرض المحادثات'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],
            ['name' => 'chats.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Chat', 'ar' => 'حذف محادثة'], 'group' => 'operations', 'group_name' => ['en' => 'Operations', 'ar' => 'عمليات داخل الموقع']],

            // Countries & Cities
            ['name' => 'countries.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Countries', 'ar' => 'عرض الدول'], 'group' => 'countries', 'group_name' => ['en' => 'Countries', 'ar' => 'الدول']],
            ['name' => 'countries.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Country', 'ar' => 'إضافة دولة'], 'group' => 'countries', 'group_name' => ['en' => 'Countries', 'ar' => 'الدول']],
            ['name' => 'countries.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Country', 'ar' => 'تعديل دولة'], 'group' => 'countries', 'group_name' => ['en' => 'Countries', 'ar' => 'الدول']],
            ['name' => 'countries.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Country', 'ar' => 'حذف دولة'], 'group' => 'countries', 'group_name' => ['en' => 'Countries', 'ar' => 'الدول']],

            ['name' => 'cities.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Cities', 'ar' => 'عرض المدن'], 'group' => 'cities', 'group_name' => ['en' => 'Cities', 'ar' => 'المدن']],
            ['name' => 'cities.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create City', 'ar' => 'إضافة مدينة'], 'group' => 'cities', 'group_name' => ['en' => 'Cities', 'ar' => 'المدن']],
            ['name' => 'cities.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit City', 'ar' => 'تعديل مدينة'], 'group' => 'cities', 'group_name' => ['en' => 'Cities', 'ar' => 'المدن']],
            ['name' => 'cities.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete City', 'ar' => 'حذف مدينة'], 'group' => 'cities', 'group_name' => ['en' => 'Cities', 'ar' => 'المدن']],

            // Categories
            ['name' => 'categories.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Categories', 'ar' => 'عرض الاقسام'], 'group' => 'categories', 'group_name' => ['en' => 'Categories', 'ar' => 'الاقسام']],
            ['name' => 'categories.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Category', 'ar' => 'إضافة قسم'], 'group' => 'categories', 'group_name' => ['en' => 'Categories', 'ar' => 'الاقسام']],
            ['name' => 'categories.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Category', 'ar' => 'تعديل قسم'], 'group' => 'categories', 'group_name' => ['en' => 'Categories', 'ar' => 'الاقسام']],
            ['name' => 'categories.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Category', 'ar' => 'حذف قسم'], 'group' => 'categories', 'group_name' => ['en' => 'Categories', 'ar' => 'الاقسام']],

            // Memberships
            ['name' => 'memberships.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Service Providers', 'ar' => 'عرض مقدمي الخدمات'], 'group' => 'memberships', 'group_name' => ['en' => 'Service Providers', 'ar' => 'مقدمي الخدمات']],
            ['name' => 'memberships.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Service Provider', 'ar' => 'تعديل مقدم خدمة'], 'group' => 'memberships', 'group_name' => ['en' => 'Service Providers', 'ar' => 'مقدمي الخدمات']],
            ['name' => 'memberships.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Service Provider', 'ar' => 'حذف مقدم خدمة'], 'group' => 'memberships', 'group_name' => ['en' => 'Service Providers', 'ar' => 'مقدمي الخدمات']],
            ['name' => 'memberships.update-status', 'guard_name' => 'admin', 'display_name' => ['en' => 'Update Status', 'ar' => 'تحديث حالة مقدم الخدمة'], 'group' => 'memberships', 'group_name' => ['en' => 'Service Providers', 'ar' => 'مقدمي الخدمات']],

            // Subscription Packages
            ['name' => 'subscription-packages.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Packages', 'ar' => 'عرض الباقات'], 'group' => 'subscription-packages', 'group_name' => ['en' => 'Packages', 'ar' => 'الباقات']],
            ['name' => 'subscription-packages.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Package', 'ar' => 'إضافة باقة'], 'group' => 'subscription-packages', 'group_name' => ['en' => 'Packages', 'ar' => 'الباقات']],
            ['name' => 'subscription-packages.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Package', 'ar' => 'تعديل باقة'], 'group' => 'subscription-packages', 'group_name' => ['en' => 'Packages', 'ar' => 'الباقات']],
            ['name' => 'subscription-packages.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Package', 'ar' => 'حذف باقة'], 'group' => 'subscription-packages', 'group_name' => ['en' => 'Packages', 'ar' => 'الباقات']],

            // Services
            ['name' => 'services.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Services', 'ar' => 'عرض الخدمات'], 'group' => 'services', 'group_name' => ['en' => 'Services', 'ar' => 'الخدمات']],
            ['name' => 'services.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Service', 'ar' => 'إضافة خدمة'], 'group' => 'services', 'group_name' => ['en' => 'Services', 'ar' => 'الخدمات']],
            ['name' => 'services.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Service', 'ar' => 'تعديل خدمة'], 'group' => 'services', 'group_name' => ['en' => 'Services', 'ar' => 'الخدمات']],
            ['name' => 'services.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Service', 'ar' => 'حذف خدمة'], 'group' => 'services', 'group_name' => ['en' => 'Services', 'ar' => 'الخدمات']],

            // Success Partners
            ['name' => 'success-partners.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Partners', 'ar' => 'عرض شركاء النجاح'], 'group' => 'success-partners', 'group_name' => ['en' => 'Success Partners', 'ar' => 'شركاء النجاح']],
            ['name' => 'success-partners.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Partner', 'ar' => 'إضافة شريك'], 'group' => 'success-partners', 'group_name' => ['en' => 'Success Partners', 'ar' => 'شركاء النجاح']],
            ['name' => 'success-partners.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Partner', 'ar' => 'تعديل شريك'], 'group' => 'success-partners', 'group_name' => ['en' => 'Success Partners', 'ar' => 'شركاء النجاح']],
            ['name' => 'success-partners.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Partner', 'ar' => 'حذف شريك'], 'group' => 'success-partners', 'group_name' => ['en' => 'Success Partners', 'ar' => 'شركاء النجاح']],

            // Service Requests
            ['name' => 'service-requests.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Requests', 'ar' => 'عرض الطلبات'], 'group' => 'service-requests', 'group_name' => ['en' => 'Requests', 'ar' => 'الطلبات']],
            ['name' => 'service-requests.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Request', 'ar' => 'تعديل طلب'], 'group' => 'service-requests', 'group_name' => ['en' => 'Requests', 'ar' => 'الطلبات']],
            ['name' => 'service-requests.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Request', 'ar' => 'حذف طلب'], 'group' => 'service-requests', 'group_name' => ['en' => 'Requests', 'ar' => 'الطلبات']],
            ['name' => 'service-requests.change-status', 'guard_name' => 'admin', 'display_name' => ['en' => 'Change Request Status', 'ar' => 'تغيير حالة الطلب'], 'group' => 'service-requests', 'group_name' => ['en' => 'Requests', 'ar' => 'الطلبات']],

            // Pages
            ['name' => 'pages.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Pages', 'ar' => 'عرض الصفحات'], 'group' => 'pages', 'group_name' => ['en' => 'Pages', 'ar' => 'الصفحات']],
            ['name' => 'pages.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Page', 'ar' => 'إضافة صفحة'], 'group' => 'pages', 'group_name' => ['en' => 'Pages', 'ar' => 'الصفحات']],
            ['name' => 'pages.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Page', 'ar' => 'تعديل صفحة'], 'group' => 'pages', 'group_name' => ['en' => 'Pages', 'ar' => 'الصفحات']],
            ['name' => 'pages.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Page', 'ar' => 'حذف صفحة'], 'group' => 'pages', 'group_name' => ['en' => 'Pages', 'ar' => 'الصفحات']],

            // FAQs
            ['name' => 'faqs.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View FAQs', 'ar' => 'عرض الأسئلة الشائعة'], 'group' => 'faqs', 'group_name' => ['en' => 'FAQs', 'ar' => 'الأسئلة الشائعة']],
            ['name' => 'faqs.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create FAQ', 'ar' => 'إضافة سؤال'], 'group' => 'faqs', 'group_name' => ['en' => 'FAQs', 'ar' => 'الأسئلة الشائعة']],
            ['name' => 'faqs.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit FAQ', 'ar' => 'تعديل سؤال'], 'group' => 'faqs', 'group_name' => ['en' => 'FAQs', 'ar' => 'الأسئلة الشائعة']],
            ['name' => 'faqs.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete FAQ', 'ar' => 'حذف سؤال'], 'group' => 'faqs', 'group_name' => ['en' => 'FAQs', 'ar' => 'الأسئلة الشائعة']],

            // Activity Types
            ['name' => 'activity-types.index', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Activity Types', 'ar' => 'عرض أنواع الأنشطة'], 'group' => 'activity-types', 'group_name' => ['en' => 'Activity Types', 'ar' => 'أنواع الأنشطة البيئية']],
            ['name' => 'activity-types.create', 'guard_name' => 'admin', 'display_name' => ['en' => 'Create Activity Type', 'ar' => 'إضافة نوع نشاط'], 'group' => 'activity-types', 'group_name' => ['en' => 'Activity Types', 'ar' => 'أنواع الأنشطة البيئية']],
            ['name' => 'activity-types.edit', 'guard_name' => 'admin', 'display_name' => ['en' => 'Edit Activity Type', 'ar' => 'تعديل نوع نشاط'], 'group' => 'activity-types', 'group_name' => ['en' => 'Activity Types', 'ar' => 'أنواع الأنشطة البيئية']],
            ['name' => 'activity-types.delete', 'guard_name' => 'admin', 'display_name' => ['en' => 'Delete Activity Type', 'ar' => 'حذف نوع نشاط'], 'group' => 'activity-types', 'group_name' => ['en' => 'Activity Types', 'ar' => 'أنواع الأنشطة البيئية']],

            // Settings
            ['name' => 'settings.get', 'guard_name' => 'admin', 'display_name' => ['en' => 'View Settings', 'ar' => 'عرض الإعدادات'], 'group' => 'settings', 'group_name' => ['en' => 'Settings', 'ar' => 'الاعدادات']],
            ['name' => 'settings.post', 'guard_name' => 'admin', 'display_name' => ['en' => 'Update Settings', 'ar' => 'تحديث الإعدادات'], 'group' => 'settings', 'group_name' => ['en' => 'Settings', 'ar' => 'الاعدادات']],
        ];

        $allPermissionNames = collect($permissions)->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                [
                    'display_name' => $permission['display_name'], 
                    'group_name' => $permission['group_name'], 
                    'group' => $permission['group']
                ],
            );
        }

        // Clean up permissions that are no longer in the seeder for the admin guard
        Permission::where('guard_name', 'admin')
            ->whereNotIn('name', $allPermissionNames)
            ->delete();
    }
}
