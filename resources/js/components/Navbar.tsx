type NavbarProps = {
    user: any;
    onLogout: () => void;
};

export default function Navbar({ user, onLogout }: NavbarProps) {
    return (
        <div className="navbar">
            <div className="nav-left">
                <h3>Welcome, {user?.name || "User"}</h3>
            </div>

            <div className="nav-right">
                <span className="user-role">{user?.role}</span>

                <button className="logout-btn" onClick={onLogout}>
                    Logout
                </button>
            </div>
        </div>
    );
}