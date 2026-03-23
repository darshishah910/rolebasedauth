export const validateProduct = (form: any, isEdit: boolean = false) => {
    const errors: any = {};

    // Name
    if (!form.name || form.name.trim() === "") {
        errors.name = "Product name is required";
    } else if (form.name.length > 255) {
        errors.name = "Max 255 characters allowed";
    }

    // Price
    if (!form.price) {
        errors.price = "Price is required";
    } else if (isNaN(form.price)) {
        errors.price = "Price must be a number";
    } else if (Number(form.price) < 0) {
        errors.price = "Price cannot be negative";
    }

    // Quantity
    if (!form.quantity) {
        errors.quantity = "Quantity is required";
    } else if (isNaN(form.quantity)) {
        errors.quantity = "Quantity must be a number";
    } else if (Number(form.quantity) < 0) {
        errors.quantity = "Quantity cannot be negative";
    }

    // Image (only required on create)
    if (!isEdit && !form.image) {
        errors.image = "Product image is required";
    }

    // Stock
    if (![0, 1].includes(Number(form.in_stock))) {
        errors.in_stock = "Invalid stock value";
    }

    return errors;
};