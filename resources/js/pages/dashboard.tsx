import { useEffect, useState } from "react";
import axios from "../lib/axios";
import "../styles/style.css";
import Sidebar from "../components/Sidebar";
import Navbar from "../components/Navbar";

export default function Dashboard() {
    const [stats, setStats] = useState({
        total: 0,
        active: 0,
        inactive: 0,
    });

    const [user, setUser] = useState<any>(null);
    const [users, setUsers] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);

    // ✅ Toggle User Status
    const toggleStatus = async (id: number, value: number) => {
        try {
            await axios.post(`/user/toggle/${id}`, {
                is_active: value,
            });

            setUsers(prev => {
                const updated = prev.map(u =>
                    u.id === id ? { ...u, is_active: value } : u
                );

                const active = updated.filter(u => u.is_active === 1).length;
                const inactive = updated.filter(u => u.is_active === 0).length;

                setStats(prevStats => ({
                    ...prevStats,
                    active,
                    inactive
                }));

                return updated;
            });

        } catch (error) {
            console.error("Toggle Error:", error);
        }
    };

    // ✅ Logout
    const handleLogout = async () => {
        try {
            await axios.post("/logout");
        } catch (e) { }

        localStorage.removeItem("token");
        window.location.href = "/login";
    };

    // ✅ Initial Load
    useEffect(() => {
        const token = localStorage.getItem("token");
        
        if (!token) {
            window.location.href = "/login";
            return;
        }

        const init = async () => {
            try {
                // Profile
                const profileRes = await axios.get("/user");
                const loggedUser = profileRes.data.user;
                setUser(loggedUser);

                // Stats
                const statsRes = await axios.get("/stats");
                setStats(statsRes.data);

                // Users (Admin only)
                if (loggedUser.role === "admin") {
                    const usersRes = await axios.get("/users");
                    setUsers(usersRes.data.data);
                }

            } catch (error) {
                console.error("Init Error:", error);
            } finally {
                setLoading(false);
            }
        };

        init();
    }, []);

    if (loading) {
        return <h3 style={{ textAlign: "center" }}>Loading...</h3>;
    }

    return (
        <div className="container">
            {/* ✅ Sidebar */}
            <Sidebar user={user} />

            {/* ✅ Main */}
            <div className="main">
                {/* ✅ Navbar */}
                <Navbar user={user} onLogout={handleLogout} />

                {/* ✅ Content */}
                <div className="content">
                    <div className="dashboard">

                        <h2>Dashboard</h2>

                        {/* ✅ PROFILE */}
                        {user && (
                            <div className="profile-card">
                                <img
                                    src={
                                        user.image
                                            ? `/storage/${user.image}`
                                            : "/default.png"
                                    }
                                    className="profile-img"
                                    alt="profile"
                                />

                                <div className="profile-info">
                                    <h3>{user.name}</h3>
                                    <p>{user.email}</p>

                                    <span className={`role-badge ${user.role}`}>
                                        {user.role}
                                    </span>

                                </div>
                            </div>
                        )}

                        {/* ✅ STATS */}
                        {user?.role === "admin" && (
                            <div className="stats">
                                <div className="stat-card">
                                    <h3>Total Users</h3>
                                    <p>{stats.total}</p>
                                </div>

                                <div className="stat-card">
                                    <h3>Active Users</h3>
                                    <p>{stats.active}</p>
                                </div>

                                <div className="stat-card">
                                    <h3>Inactive Users</h3>
                                    <p>{stats.inactive}</p>
                                </div>
                            </div>
                        )}

                        {/* ✅ USER TABLE */}
                        {user?.role === "admin" && (
                            <table className="user-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {users.map((u) => (
                                        <tr key={u.id}>
                                            <td>{u.name}</td>
                                            <td>{u.email}</td>
                                            <td>
                                                <span className={`role-badge ${u.role}`}>
                                                    {u.role}
                                                </span>
                                            </td>

                                            <td>
                                                <select
                                                    value={u.is_active ? 1 : 0}
                                                    onChange={(e) =>
                                                        toggleStatus(
                                                            u.id,
                                                            Number(e.target.value)
                                                        )
                                                    }
                                                >
                                                    <option value={1}>Active</option>
                                                    <option value={0}>Inactive</option>
                                                </select>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        )}

                    </div>
                </div>
            </div>
        </div>
    );
}