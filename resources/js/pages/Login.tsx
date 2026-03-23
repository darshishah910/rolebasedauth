import { useState } from 'react';
import api from '@/lib/axios';
import '../styles/style.css';
import { validateLogin } from '../validations/validateLogin ';
import toast, { Toaster } from 'react-hot-toast';

export default function Login() {
    const [form, setForm] = useState({
        email: '',
        password: '',
    });

    const [errors, setErrors] = useState<any>({});
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);

    const handleChange = (e: any) => {
        const { name, value } = e.target;

        setForm({ ...form, [name]: value });

        // ✅ clear field error while typing
        setErrors((prev: any) => ({
            ...prev,
            [name]: null
        }));
    };

    const handleSubmit = async (e: any) => {
        e.preventDefault();

        if (loading) return;

        // ✅ frontend validation
        const validationErrors = validateLogin(form);

        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        setLoading(true);

        try {
            const res = await api.post('/login', form);

            // ✅ save token
            localStorage.setItem('token', res.data.data.token);

            toast.success('Login successful');

            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 1000);

        } catch (error: any) {

            if (error.response?.status === 422) {
                setErrors(error.response.data.errors);
            } else if (error.response?.status === 401) {
                toast.error('Invalid credentials');
            } else {
                toast.error('Login failed');
            }

        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="auth-container">
            <Toaster position="top-right" />

            <form onSubmit={handleSubmit} className="auth-form">
                <h2>Login</h2>

                {/* EMAIL */}
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value={form.email}
                    onChange={handleChange}
                />

                {errors.email && (
                    <p className="error">
                        {Array.isArray(errors.email)
                            ? errors.email[0]
                            : errors.email}
                    </p>
                )}

                {/* PASSWORD */}
                <div style={{ position: 'relative' }}>
                    <input
                        type={showPassword ? 'text' : 'password'}
                        name="password"
                        placeholder="Password"
                        value={form.password}
                        onChange={handleChange}
                    />

                    <span
                        onClick={() => setShowPassword(!showPassword)}
                        style={{
                            position: 'absolute',
                            right: '10px',
                            top: '50%',
                            transform: 'translateY(-50%)',
                            cursor: 'pointer',
                            fontSize: '14px',
                            color: '#007bff'
                        }}
                    >
                        {showPassword ? 'Hide' : 'Show'}
                    </span>
                </div>

                {errors.password && (
                    <p className="error">
                        {Array.isArray(errors.password)
                            ? errors.password[0]
                            : errors.password}
                    </p>
                )}

                {/* BUTTON */}
                <button disabled={loading}>
                    {loading ? 'Logging in...' : 'Login'}
                </button>

                {/* LINK */}
                <p className="switch-link">
                    Don’t have an account?{' '}
                    <a href="/register">Register</a>
                </p>
            </form>
        </div>
    );
}