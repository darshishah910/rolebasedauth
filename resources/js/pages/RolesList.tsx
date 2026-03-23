import { useEffect, useState } from "react";
import axios from "../lib/axios";
import Sidebar from "../components/Sidebar";
import Navbar from "../components/Navbar";
import "../styles/style.css";

export default function RolesList() {
    const user = { name: "Admin", role: "admin" };

    const handleLogout = () => {};

    const [users, setUsers] = useState<any[]>([]);

    const fetchUsers = async () => {
        const res = await axios.get("/all-users-with-permissions");
        setUsers(res.data);
    };

    useEffect(() => {
        fetchUsers();
    }, []);

    return (
        <div className="container">
            <Sidebar user={user} />

            <div className="main">
                <Navbar user={user} onLogout={handleLogout} />

                <div className="content">
                    <h2>User Permissions</h2>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Permissions</th>
                            </tr>
                        </thead>

                        <tbody>
                            {users.map(user => (
                                <tr key={user.id}>
                                    <td>{user.name}</td>
                                    <td>{user.role}</td>
                                    <td>{user.permissions.join(", ")}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}