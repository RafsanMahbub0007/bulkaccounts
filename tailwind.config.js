import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "selector",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            typography: {
                DEFAULT: {
                    css: {
                        color: "#E5E7EB",
                        a: {
                            color: "#EF4444",
                            "&:hover": {
                                color: "#F87171",
                            },
                        },
                        h1: {
                            color: "#EF4444",
                            fontWeight: "700",
                            fontSize: "3rem", // 48px
                            lineHeight: "1.2",
                        },
                        h2: {
                            color: "#EF4444",
                            fontWeight: "600",
                            fontSize: "2.25rem", // 36px
                            lineHeight: "1.3",
                        },
                        h3: {
                            color: "#EF4444",
                            fontWeight: "600",
                            fontSize: "1.875rem", // 30px
                            lineHeight: "1.4",
                        },
                        h4: {
                            color: "#EF4444",
                            fontWeight: "500",
                            fontSize: "1.5rem", // 24px
                            lineHeight: "1.4",
                        },
                        h5: {
                            color: "#EF4444",
                            fontWeight: "500",
                            fontSize: "1.25rem", // 20px
                            lineHeight: "1.5",
                        },
                        h6: {
                            color: "#EF4444",
                            fontWeight: "500",
                            fontSize: "1.125rem", // 18px
                            lineHeight: "1.6",
                        },
                        p: {
                            color: "#D1D5DB",
                            fontWeight: "400",
                            fontSize: "1rem", // 16px
                            lineHeight: "1.75",
                        },
                        blockquote: {
                            borderLeftColor: "#EF4444",
                            color: "#D1D5DB",
                            fontStyle: "italic",
                            paddingLeft: "1rem",
                            marginLeft: "0",
                            fontSize: "1.125rem", // 18px
                            lineHeight: "1.6",
                        },
                        code: {
                            color: "#F87171",
                        },
                        pre: {
                            backgroundColor: "#2D3748",
                            padding: "1rem",
                            borderRadius: "0.375rem",
                            overflowX: "auto",
                            color: "#F87171",
                            fontSize: "1rem", // 16px
                        },
                        "ul > li::before": {
                            backgroundColor: "#EF4444",
                        },
                        "ol > li::before": {
                            backgroundColor: "#EF4444",
                        },
                        table: {
                            borderCollapse: "collapse",
                            width: "100%",
                        },
                        "table th, table td": {
                            padding: "0.75rem",
                            borderBottomWidth: "1px",
                            borderBottomColor: "#2D3748",
                        },
                        "table th": {
                            color: "#EF4444",
                            textAlign: "left",
                            fontWeight: "600",
                            fontSize: "1.125rem", // 18px
                        },
                        "table td": {
                            color: "#D1D5DB",
                            fontSize: "1rem", // 16px
                        },
                        hr: {
                            borderTopColor: "#EF4444",
                        },
                        strong: {
                            color: "#EF4444",
                            fontWeight: "600",
                        },
                        em: {
                            fontStyle: "italic",
                            color: "#D1D5DB",
                        },
                        sub: {
                            color: "#D1D5DB",
                        },
                        sup: {
                            color: "#D1D5DB",
                        },
                    },
                },
            },
        },
    },

    plugins: [forms, typography],
};
