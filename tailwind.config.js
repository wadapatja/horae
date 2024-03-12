/** @type {import('tailwindcss').Config} */
export default {
  content: [
      "./resources/**/*.vue",
      "./resources/**/*.js",
      "./resources/**/*.blade.php",
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
