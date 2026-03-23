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

    // 🔥 NEW STATES
    const [search, setSearch] = useState("");
    const [page, setPage] = useState(1);
    const [pagination, setPagination] = useState<any>({});

    // ✅ Fetch Users (Search + Pagination)
    const fetchUsers = async () => {
        try {
            const res = await axios.get("/users", {
                params: { search, page }
            });

            setUsers(res.data.data);
            setPagination(res.data);

        } catch (err) {
            console.error("User Fetch Error:", err);
        }
    };

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
        } catch (e) {}

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

                // Users (Admin)
                if (loggedUser.role === "admin") {
                    await fetchUsers();
                }

            } catch (error) {
                console.error("Init Error:", error);
            } finally {
                setLoading(false);
            }
        };

        init();
    }, []);

    // ✅ Search + Pagination Effect (Debounce)
    useEffect(() => {
        if (!user || user.role !== "admin") return;

        const delay = setTimeout(() => {
            fetchUsers();
        }, 400);

        return () => clearTimeout(delay);
    }, [search, page]);

    if (loading) {
        return <h3 style={{ textAlign: "center" }}>Loading...</h3>;
    }

    return (
        <div className="container">
            {/* Sidebar */}
            <Sidebar user={user} />

            <div className="main">
                <Navbar user={user} onLogout={handleLogout} />

                <div className="content">
                    <div className="dashboard">

                        <h2>Dashboard</h2>

                        {/* PROFILE */}
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

                        {/* STATS */}
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

                        {/* SEARCH */}
                        {user?.role === "admin" && (
                            <input
                                type="text"
                                placeholder="Search users..."
                                value={search}
                                onChange={(e) => {
                                    setSearch(e.target.value);
                                    setPage(1); // reset page
                                }}
                                className="search-input"
                            />
                        )}

                        {/* TABLE */}
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

                        {/* PAGINATION */}
                        {user?.role === "admin" && (
                            <div className="pagination">
                                <button
                                    disabled={page === 1}
                                    onClick={() => setPage(page - 1)}
                                >
                                    Prev
                                </button>

                                <span>
                                    Page {pagination.current_page || 1} of {pagination.last_page || 1}
                                </span>

                                <button
                                    disabled={page === pagination.last_page}
                                    onClick={() => setPage(page + 1)}
                                >
                                    Next
                                </button>
                            </div>
                        )}

                    </div>
                </div>
            </div>
        </div>
    );
}