export type * from './auth';

// ✅ Permission
export interface Permission {
    id?: number;
    name: string;
}

// ✅ Role
export interface Role {
    id: number;
    name: string;
    permissions: string[]; // only names
}

// ✅ User (Spatie)
export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    bio?: string;
    image?: string;
    is_active?: boolean;

    roles: {
        id: number;
        name: string;
    }[];

    permissions?: Permission[];
}

// ✅ API Response (Roles Page)
export interface RoleResponse {
    roles: Role[];
    permissions: string[];
}