export const validateRegister = (form: any) => {
    const errors: any = {};

    const emailRegex = /\S+@\S+\.\S+/;
    const phoneRegex = /^\d{10}$/;
    const passwordRegex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/;
    const allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png'];

    // ✅ Name
    if (!form.name?.trim()) {
        errors.name = 'Name is required';
    } else if (form.name.length < 2) {
        errors.name = 'Name must be at least 2 characters';
    } else if (form.name.length > 25) {
        errors.name = 'Name must not exceed 25 characters';
    }

    // ✅ Email
    if (!form.email?.trim()) {
        errors.email = 'Email is required';
    } else if (!emailRegex.test(form.email)) {
        errors.email = 'Enter a valid email address';
    }

    // ✅ Phone
    if (!form.phone) {
        errors.phone = 'Phone number is required';
    } else if (!phoneRegex.test(form.phone)) {
        errors.phone = 'Phone number must be exactly 10 digits';
    }

    // ✅ Bio
    if (!form.bio?.trim()) {
        errors.bio = 'Bio is required';
    } else if (form.bio.length > 500) {
        errors.bio = 'Bio must not exceed 500 characters';
    }

    // ✅ Image
    if (!form.image) {
        errors.image = 'Profile image is required';
    } else if (!allowedImageTypes.includes(form.image.type)) {
        errors.image = 'Only JPG, JPEG, PNG files are allowed';
    } else if (form.image.size > 2048 * 1024) {
        errors.image = 'Image must be less than 2MB';
    }

    // ✅ Password
    if (!form.password) {
        errors.password = 'Password is required';
    } else if (form.password.length < 6) {
        errors.password = 'Password must be at least 6 characters';
    } else if (form.password.length > 10) {
        errors.password = 'Password must not exceed 10 characters';
    } else if (!passwordRegex.test(form.password)) {
        errors.password =
            'Password must contain 1 uppercase, 1 number, and 1 special character';
    }

    // ✅ Confirm Password
    if (!form.password_confirmation) {
        errors.password_confirmation = 'Confirm password is required';
    } else if (form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Passwords do not match';
    }

    return errors;
};