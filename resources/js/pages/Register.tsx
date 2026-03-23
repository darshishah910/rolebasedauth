import { useState } from 'react';
import { Link } from '@inertiajs/react';
import axios from '../lib/axios';
import toast, { Toaster } from 'react-hot-toast';
import { validateRegister } from '../validations/authvalidation';
import '../styles/style.css';

export default function Register() {
    const [form, setForm] = useState({
        name: '',
        email: '',
        phone: '',
        bio: '',
        password: '',
        password_confirmation: '',
        image: null as File | null,
    });

    const [errors, setErrors] = useState<any>({});
    const [loading, setLoading] = useState(false);

    // 👁️ Show/Hide password
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    // ✅ Handle Input
    const handleChange = (e: any) => {
        const { name, value, files } = e.target;

        // Clear error when typing
        setErrors((prev: any) => ({ ...prev, [name]: null }));

        if (name === 'phone') {
            const cleaned = value.replace(/\D/g, '').slice(0, 10);
            setForm({ ...form, phone: cleaned });
        } else if (name === 'image') {
            setForm({ ...form, image: files[0] });
        } else {
            setForm({ ...form, [name]: value });
        }
    };

    // ✅ Submit
    const handleSubmit = async (e: any) => {
        e.preventDefault();

        if (loading) return;

        const validationErrors = validateRegister(form);

        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            // toast.error('Please fix validation errors');
            return;
        }

        setLoading(true);

        const formData = new FormData();
        Object.keys(form).forEach((key: any) => {
            // @ts-ignore
            formData.append(key, form[key]);
        });

        try {
            await axios.post('/register', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
                withCredentials: true,
            });

            toast.success('Registration successful!');

                window.location.href = '/login';

        } catch (error: any) {
            if (error.response?.status === 422) {
                setErrors(error.response.data.errors);
                toast.error('Validation failed!');
            } else {
                toast.error('Something went wrong');
            }
        } finally {
            setLoading(false);
        }
    };

    // ✅ Helper to show Laravel + frontend errors
    const getError = (field: string) => {
        if (!errors[field]) return null;
        return Array.isArray(errors[field]) ? errors[field][0] : errors[field];
    };

    return (
        <div className="auth-container">
            <Toaster position="top-right" />

            <form onSubmit={handleSubmit} className="auth-form">
                <h2>Register</h2>

                {/* Name */}
                <input name="name" placeholder="Name" onChange={handleChange} />
                {getError('name') && <p className="error">{getError('name')}</p>}

                {/* Email */}
                <input type="email" name="email" placeholder="Email" onChange={handleChange} />
                {getError('email') && <p className="error">{getError('email')}</p>}

                {/* Phone */}
                <input
                    name="phone"
                    placeholder="Phone"
                    value={form.phone}
                    onChange={handleChange}
                />
                {getError('phone') && <p className="error">{getError('phone')}</p>}

                {/* Bio */}
                <textarea name="bio" placeholder="Bio" onChange={handleChange}></textarea>
                {getError('bio') && <p className="error">{getError('bio')}</p>}

                {/* Image */}
                <input type="file" name="image" onChange={handleChange} />
                {getError('image') && <p className="error">{getError('image')}</p>}

                {/* Password */}
                <div className="password-field">
                    <input
                        type={showPassword ? 'text' : 'password'}
                        name="password"
                        placeholder="Password"
                        onChange={handleChange}
                    />
                    <span
                        className="toggle"
                        onClick={() => setShowPassword(!showPassword)}
                    >
                        {showPassword ? 'Hide' : 'Show'}
                    </span>
                </div>
                {getError('password') && <p className="error">{getError('password')}</p>}

                {/* Password Hint */}
                <small className="hint">
                    Must include: 1 uppercase, 1 number, 1 special character (@$!%*?&)
                </small>

                {/* Confirm Password */}
                <div className="password-field">
                    <input
                        type={showConfirmPassword ? 'text' : 'password'}
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        onChange={handleChange}
                    />
                    <span
                        className="toggle"
                        onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                    >
                        {showConfirmPassword ? 'Hide' : 'Show'}
                    </span>
                </div>
                {getError('password_confirmation') && (
                    <p className="error">{getError('password_confirmation')}</p>
                )}

                {/* Button */}
                <button disabled={loading}>
                    {loading ? 'Registering...' : 'Register'}
                </button>

                {/* Link */}
                <p className="switch-link">
                    Already have an account? <Link href="/login">Login</Link>
                </p>
            </form>
        </div>
    );
}