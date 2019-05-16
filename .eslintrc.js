module.exports = {
    extends: ["prettier", "standard"],
    plugins: ["prettier"],
    rules: {
        "no-console": process.env.NODE_ENV === "production" ? "error" : "off",
        "no-debugger": process.env.NODE_ENV === "production" ? "error" : "off",
        "prettier/prettier": "error",
        "jsx-a11y/href-no-hash": [0],
    },
    parser: "babel-eslint",
    parserOptions: {
        ecmaFeatures: {
            jsx: true,
            modules: true,
        },
    },
    env: {
        browser: true,
        node: true,
    },
    globals: {
        Array: true,
        window: true,
        document: true,
        navigator: true,
    },
};
