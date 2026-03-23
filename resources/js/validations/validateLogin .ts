export const validateLogin = (form: any) => {
    const errors: any = {};
    const emailRegex = /\S+@\S+\.\S+/;

    if (!form.email?.trim()) {
        errors.email = 'Email is required';
    } else if (!emailRegex.test(form.email)) {
        errors.email = 'Enter a valid email';
    }

    if (!form.password) {
        errors.password = 'Password is required';
    }

    return errors;
};