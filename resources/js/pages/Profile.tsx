import { useEffect, useState } from "react";
import axios from "../lib/axios";
import Sidebar from "../components/Sidebar";
import Navbar from "../components/Navbar";
import { toast, Toaster } from "react-hot-toast";

export default function Profile() {
    const [user, setUser] = useState<any>(null);
    const [loading, setLoading] = useState(false);

    const [form, setForm] = useState({
        name: "",
        email: "",
        phone: "",
        bio: "",
        image: null as File | null
    });

    const [passwordForm, setPasswordForm] = useState({
        current_password: "",
        new_password: "",
        confirm_password: ""
    });

    const [preview, setPreview] = useState<string | null>(null);

    // ✅ Fetch profile
    const fetchProfile = async () => {
        const res = await axios.get("/profile");
        const data = res.data.data;

        setUser(data);

        setForm({
            name: data.name,
            email: data.email,
            phone: data.phone || "",
            bio: data.bio || "",
            image: null
        });

        setPreview(data.image);
    };

    useEffect(() => {
        fetchProfile();
    }, []);

    // ✅ Handle input
    const handleChange = (e: any) => {
        const { name, value, files } = e.target;

        if (name === "image") {
            const file = files[0];
            setForm({ ...form, image: file });

            if (file) {
                setPreview(URL.createObjectURL(file));
            }
        } else {
            setForm({ ...form, [name]: value });
        }
    };

    // ✅ Update profile
    const updateProfile = async () => {
        try {
            setLoading(true);

            const data = new FormData();

            Object.entries(form).forEach(([key, value]) => {
                if (value !== null) {
                    data.append(key, value as any);
                }
            });

            await axios.post("/profile", data);

            toast.success("Profile updated");
            fetchProfile();

        } catch (e) {
            toast.error("Update failed");
        } finally {
            setLoading(false);
        }
    };

    // ✅ Change password
    const changePassword = async () => {
        if (passwordForm.new_password !== passwordForm.confirm_password) {
            toast.error("Passwords do not match");
            return;
        }

        try {
            await axios.post("/change-password", passwordForm);
            toast.success("Password changed");

            setPasswordForm({
                current_password: "",
                new_password: "",
                confirm_password: ""
            });

        } catch {
            toast.error("Error changing password");
        }
    };

    if (!user) return <h3>Loading...</h3>;

    return (
        <div className="layout">
            <Toaster />

            <Sidebar user={user} />

            <div className="main-content">
                <Navbar user={user} onLogout={() => {}} />

                <div className="dashboard-container">
                    <h2>My Profile</h2>

                    {/* ✅ PROFILE CARD */}
                    <div className="profile-card">
                        <img
                            src={preview || "/default.png"}
                            className="profile-img"
                        />

                        <div>
                            <h3>{user.name}</h3>
                            <p>{user.email}</p>
                        </div>
                    </div>

                    {/* ✅ UPDATE FORM */}
                    <div className="profile-form">
                        <h3>Update Profile</h3>

                        <input name="name" value={form.name} onChange={handleChange} placeholder="Name" />
                        <input name="email" value={form.email} onChange={handleChange} placeholder="Email" />
                        <input name="phone" value={form.phone} onChange={handleChange} placeholder="Phone" />
                        <textarea name="bio" value={form.bio} onChange={handleChange} placeholder="Bio" />

                        <input type="file" name="image" onChange={handleChange} />

                        <button onClick={updateProfile} disabled={loading}>
                            {loading ? "Updating..." : "Update Profile"}
                        </button>
                    </div>

                    {/* ✅ PASSWORD */}
                    <div className="password-box">
                        <h3>Change Password</h3>

                        <input
                            type="password"
                            placeholder="Current Password"
                            value={passwordForm.current_password}
                            onChange={(e) =>
                                setPasswordForm({ ...passwordForm, current_password: e.target.value })
                            }
                        />

                        <input
                            type="password"
                            placeholder="New Password"
                            value={passwordForm.new_password}
                            onChange={(e) =>
                                setPasswordForm({ ...passwordForm, new_password: e.target.value })
                            }
                        />

                        <input
                            type="password"
                            placeholder="Confirm Password"
                            value={passwordForm.confirm_password}
                            onChange={(e) =>
                                setPasswordForm({ ...passwordForm, confirm_password: e.target.value })
                            }
                        />

                        <button onClick={changePassword}>
                            Change Password
                        </button>
                    </div>

                </div>
            </div>
        </div>
    );
}