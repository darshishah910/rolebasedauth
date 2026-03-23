import { useEffect, useState } from "react";
import axios from "../lib/axios";
import "../styles/style.css";
import Sidebar from "../components/Sidebar";
import Navbar from "../components/Navbar";
import { toast, Toaster } from "react-hot-toast";
import { validateProduct } from "../validations/productValidation";

type ProductForm = {
    name: string;
    description: string;
    price: string;
    quantity: string;
    image: File | null;
    in_stock: number;
};

export default function Products() {
    const [products, setProducts] = useState<any[]>([]);
    const [user, setUser] = useState<any>(null);

    const [form, setForm] = useState<ProductForm>({
        name: "",
        description: "",
        price: "",
        quantity: "",
        image: null,
        in_stock: 1
    });
    const isAdmin = user?.role === "admin";

    const [editId, setEditId] = useState<number | null>(null);
    const [errors, setErrors] = useState<any>({});
    const [loading, setLoading] = useState(false);

    const [permissions, setPermissions] = useState<string[]>([]);
    const canCreate = isAdmin || permissions?.includes("create_product");
    const canEdit = isAdmin || permissions?.includes("edit_product");
    const canDelete = isAdmin || permissions?.includes("delete_product");

    const [previewImage, setPreviewImage] = useState<string | null>(null);

    // ✅ Fetch user (for sidebar/navbar)
    const fetchUser = async () => {
        const res = await axios.get("/user");
        setUser(res.data.user);
        setPermissions(res.data.permissions);
    };

    // ✅ Fetch products
    const fetchProducts = async () => {
        const res = await axios.get("/products");
        setProducts(res.data.data);
    };

    useEffect(() => {
        const token = localStorage.getItem("token");

        if (!token) {
            window.location.href = "/login";
            return;
        }

        fetchUser();
        fetchProducts();
    }, []);

    // ✅ Handle input
    const handleChange = (e: any) => {
        const { name, value, files } = e.target;

        if (name === "image") {
            const file = files[0];

            setForm({ ...form, image: file });

            if (file) {
                setPreviewImage(URL.createObjectURL(file)); // ✅ show new image
            }

        } else if (name === "in_stock") {
            setForm({ ...form, in_stock: parseInt(value) }); // ✅ safest
        } else {
            setForm({ ...form, [name]: value });
        }
    };

    // ✅ Submit
    const handleSubmit = async () => {
        if (loading) return;

        const validationErrors = validateProduct(form, !!editId);
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        try {
            setLoading(true);

            const data = new FormData();
            (Object.keys(form) as (keyof typeof form)[]).forEach((key) => {
                const value = form[key];

                if (value === null) return;

                if (value instanceof File) {
                    data.append(key, value);
                } else {
                    data.append(key, String(value));
                }
            });

            if (editId) {
                data.append("_method", "PUT");
                await axios.post(`/products/${editId}`, data);
                toast.success("Product updated");
            } else {
                await axios.post("/products", data);
                toast.success("Product created");
            }

            // reset
            setForm({
                name: "",
                description: "",
                price: "",
                quantity: "",
                image: null,
                in_stock: 1
            });

            setEditId(null);
            setErrors({});
            fetchProducts();

        } catch {
            toast.error("Error occurred");
        } finally {
            setLoading(false);
        }
    };

    // ✅ Edit
    const handleEdit = (p: any) => {
        setForm({
            name: p.name,
            description: p.description,
            price: String(p.price),
            quantity: String(p.quantity),
            image: null,
            in_stock: p.quantity > 0 ? 1 : 0
        });
        setPreviewImage(p.image);
        setEditId(p.id);
    };

    // ✅ Delete
    const handleDelete = async (id: number) => {
        if (!confirm("Are you sure?")) return;

        await axios.delete(`/products/${id}`);
        toast.success("Deleted");
        fetchProducts();
    };

    // ✅ Toggle stock
    const toggleStock = async (id: number, value: number) => {
        await axios.post(`/products/toggle/${id}`, { in_stock: value });

        setProducts(prev =>
            prev.map(p =>
                p.id === id ? { ...p, in_stock: value } : p
            )
        );
    };

    // ✅ Logout
    const handleLogout = async () => {
        await axios.post("/logout");
        localStorage.removeItem("token");
        window.location.href = "/login";
    };

    return (
        <div className="layout">
            <Toaster />

            {/* ✅ Sidebar */}
            <Sidebar user={user} />

            {/* ✅ Main */}
            <div className="main-content">

                {/* ✅ Navbar */}
                <Navbar user={user} onLogout={handleLogout} />

                <div className="product-container">
                    <h2>Products</h2>

                    {/* FORM */}
                    {(canCreate || (editId && canEdit)) && (
                    <div className="product-form">
                        <input name="name" value={form.name} onChange={handleChange} placeholder="Name" />
                        {errors.name && <p className="error">{errors.name}</p>}

                        <input type="number" name="price" value={form.price} onChange={handleChange} placeholder="Price" />
                        {errors.price && <p className="error">{errors.price}</p>}

                        <input type="number" name="quantity" value={form.quantity} onChange={handleChange} placeholder="Qty" />
                        {errors.quantity && <p className="error">{errors.quantity}</p>}

                        {editId && (
                            <div>
                                <p>Current Image:</p>
                                <img
                                    src={products.find(p => p.id === editId)?.image}
                                    className="product-img"
                                />
                            </div>
                        )}

                        <input type="file" name="image" onChange={handleChange} className="full" />

                        {/* ✅ Show new selected image */}
                        {form.image && (
                            <div>
                                <p>New Image Preview:</p>
                                <img
                                    src={URL.createObjectURL(form.image)}
                                    className="product-img"
                                />
                            </div>
                        )}

                        <select name="in_stock" value={form.in_stock} onChange={handleChange} className="full">
                            <option value={1}>In Stock</option>
                            <option value={0}>Out of Stock</option>
                        </select>

                        {(editId ? canEdit : canCreate) && (
                            <button onClick={handleSubmit} disabled={loading}>
                                {loading ? "Processing..." : editId ? "Update" : "Create"}
                            </button>
                        )}
                    </div>
                    )}

                    {/* TABLE */}
                    <table className="product-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Change</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {products.map(p => (
                                <tr key={p.id}>
                                    <td>
                                        {p.image && (
                                            <img src={p.image} className="product-img" />
                                        )}
                                    </td>

                                    <td>{p.name}</td>
                                    <td>₹{p.price}</td>
                                    <td>{p.quantity}</td>

                                    <td>
                                        <span className={`stock ${p.in_stock ? "in" : "out"}`}>
                                            {p.in_stock ? "In Stock" : "Out of Stock"}
                                        </span>
                                    </td>

                                    <td>
                                        {canEdit ? (
                                            <select
                                                value={p.in_stock}
                                                onChange={(e) =>
                                                    toggleStock(p.id, Number(e.target.value))
                                                }
                                            >
                                                <option value={1}>In Stock</option>
                                                <option value={0}>Out of Stock</option>
                                            </select>
                                        ) : (
                                            <span>{p.in_stock ? "In Stock" : "Out of Stock"}</span>
                                        )}
                                    </td>

                                    <td>
                                        {canEdit && (
                                            <button className="btn btn-edit" onClick={() => handleEdit(p)}>
                                                Edit
                                            </button>
                                        )}
                                        {canDelete && (
                                            <button className="btn btn-delete" onClick={() => handleDelete(p.id)}>
                                                Delete
                                            </button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    );
}