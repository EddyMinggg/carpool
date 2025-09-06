/** @type {import('tailwindcss').Config} */
module.exports = {
  // 1. 告诉Tailwind需要扫描哪些文件中的类（你的Blade视图）
  content: [
    './resources/views/**/*.blade.php', // 扫描所有Blade文件
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  // 2. 指定输出的CSS文件路径（编译后会生成到 public/css/tailwind.css）
  output: './public/css/tailwind.css'
}