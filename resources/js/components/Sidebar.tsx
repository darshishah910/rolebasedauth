import { Link, usePage } from "@inertiajs/react";

export default function Sidebar({ user }: any) {
    const { url } = usePage();

    const role = user?.role?.toLowerCase();

    const isActive = (path: string) => {
        return url.startsWith(path) ? "active" : "";
    };

    return (
        <div className="sidebar">
            <h2 className="logo">{role?.toUpperCase()}</h2>

            <ul className="menu">
                <li className={`menu-item ${isActive("/dashboard")}`}>
                    <Link href="/dashboard">Dashboard</Link>
                </li>

                {/* ✅ Admin */}
                {role === "admin" && (
                    <>
                        <li className={`menu-item ${isActive("/roles")}`}>
                            <Link href="/roles">Roles & Permissions</Link>
                        </li>

                        <li className={`menu-item ${isActive("/roles-list")}`}>
                            <Link href="/roles-list">User Permissions</Link>
                        </li>

                        <li className={`menu-item ${isActive("/products")}`}>
                            <Link href="/products">Products</Link>
                        </li>

                        <li className={`menu-item ${isActive("/profile")}`}>
                            <Link href="/profile">My Profile</Link>
                        </li>
                    </>
                )}

                {/* ✅ Manager */}
                {role === "manager" && (
                    <>
                        <li className={`menu-item ${isActive("/products")}`}>
                            <Link href="/products">Products</Link>
                        </li>



                        <li className={`menu-item ${isActive("/profile")}`}>
                            <Link href="/profile">My Profile</Link>
                        </li>
                    </>
                )}

                {/* ✅ User */}
                {role === "user" && (

                    <>
                        <li className={`menu-item ${isActive("/products")}`}>
                            <Link href="/products">Products</Link>
                        </li>

                        <li className={`menu-item ${isActive("/profile")}`}>
                            <Link href="/profile">My Profile</Link>
                        </li>
                    </>
                )}
            </ul>
        </div>
    );
}