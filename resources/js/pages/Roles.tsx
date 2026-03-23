import { useEffect, useState } from "react";
import axios from "../lib/axios";
import { router } from "@inertiajs/react";
import Sidebar from "../components/Sidebar";
import Navbar from "../components/Navbar";
import "../styles/style.css";

export default function Roles() {
    const user = { name: "Admin", role: "admin" };

    const handleLogout = async () => {
        try {
            await axios.post("/logout");
        } catch (e) { }

        localStorage.removeItem("token");
        window.location.href = "/login";
    };

    const [roles, setRoles] = useState<string[]>([]);
    const [permissions, setPermissions] = useState<string[]>([]);
    const [users, setUsers] = useState<any[]>([]);
    const [selectedRole, setSelectedRole] = useState("");
    const [selectedUser, setSelectedUser] = useState<number | null>(null);
    const [selectedPermissions, setSelectedPermissions] = useState<string[]>([]);
    const [loading, setLoading] = useState(true);

    const fetchData = async () => {
        const res = await axios.get("/roles");

        setRoles(
            res.data.roles
                .map((r: any) => r.name)
                .filter((role: string) => role !== "Admin")
        );

        setPermissions(res.data.permissions);
        setLoading(false);
    };

    useEffect(() => {
        const token = localStorage.getItem("token");

        if (!token) {
            window.location.href = "/login";
            return;
        }
        fetchData();
    }, []);

    const handleRoleClick = async (role: string) => {
        setSelectedRole(role);
        setSelectedUser(null);

        const res = await axios.get(`/users-by-role/${role}`);
        setUsers(res.data);
    };

    const handleUserSelect = (userId: number) => {
        if (!userId) return;

        setSelectedUser(userId);

        const user = users.find(u => u.id === userId);

        // ✅ load permissions from API response
        setSelectedPermissions(user?.permissions || []);
    };

    const togglePermission = (perm: string) => {
        setSelectedPermissions(prev =>
            prev.includes(perm)
                ? prev.filter(p => p !== perm)
                : [...prev, perm]
        );
    };

    const savePermissions = async () => {
        if (!selectedUser) return;

        await axios.post("/assign-user-permissions", {
            user_id: selectedUser,
            permissions: selectedPermissions
        });

        alert("Permissions saved ✅");
        router.visit("/roles-list");
    };

    if (loading) return <h3>Loading...</h3>;

    return (
        <div className="container">
            <Sidebar user={user} />

            <div className="main">
                <Navbar user={user} onLogout={handleLogout} />

                <div className="content">
                    <h2>Roles</h2>

                    {roles.map(role => (
                        <button
                            key={role}
                            className={`role-btn ${selectedRole === role ? "active" : ""}`}
                            onClick={() => handleRoleClick(role)}
                        >
                            {role}
                        </button>
                    ))}

                    {users.length > 0 && (
                        <select onChange={(e) => handleUserSelect(Number(e.target.value))}>
                            <option value="">Select User</option>

                            {users.map(user => (
                                <option key={user.id} value={user.id}>
                                    {user.name}
                                </option>
                            ))}
                        </select>
                    )}

                    {selectedUser && (
                        <div className="permission-box">
                            <h3>Permissions</h3>

                            {permissions.map(perm => (
                                <label key={perm}>
                                    <input
                                        type="checkbox"
                                        checked={selectedPermissions.includes(perm)}
                                        onChange={() => togglePermission(perm)}
                                    />
                                    {perm}
                                </label>
                            ))}

                            <button className="save-btn" onClick={savePermissions}>
                                Save
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}