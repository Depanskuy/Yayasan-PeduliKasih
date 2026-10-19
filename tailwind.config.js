/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./app/Core/**/*.php",
    "./public/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        // Palet Warna — Clean White & Bright Emerald
        // Hijau utama dari PDF: #059669 / #40C79A / #065F46
        base: "#F8FBF9",     // background putih bersih (tint hijau sangat tipis)
        surface: "#EEF6F1",  // surface / panel
        card: "#FFFFFF",     // card / section
        line: "#D9E7E0",     // border
        accent: {
          DEFAULT: "#059669", // emerald utama (dari PDF)
          hover: "#047857",   // emerald lebih gelap saat hover
          deep: "#10B981",    // emerald terang untuk glow/tint
          darker: "#065F46",  // hijau gelap PDF
        },
        ink: {
          DEFAULT: "#10231C", // text primary (hijau-hitam)
          soft: "#5E756B",    // text secondary
        },
        beige: "#D1FAE5",
        success: "#16A34A",
        warning: "#D97706",
        danger: "#DC2626",
      },
      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui", "-apple-system", "Segoe UI", "sans-serif"],
      },
      fontSize: {
        h1: ["1.75rem", { lineHeight: "2.125rem", fontWeight: "600" }], // 28px mobile base
        h2: ["1.5rem", { lineHeight: "2rem", fontWeight: "600" }],
        h3: ["1.25rem", { lineHeight: "1.75rem", fontWeight: "600" }],
      },
      borderRadius: {
        xl: "0.875rem",
      },
      boxShadow: {
        soft: "0 1px 2px rgba(16,35,28,.05), 0 10px 28px -14px rgba(16,35,28,.18)",
      },
    },
  },
  plugins: [],
};
