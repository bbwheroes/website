import { Asap } from "next/font/google";
import "./globals.css";
import Navigation from "./_components/Navigation/Navigation";

const asap = Asap({
  weight: ["400", "500", "700"],
  subsets: ["latin"],
});

export const metadata = {
  title: "Create Next App",
  description: "Generated by create next app",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en" className={asap.className}>
      <body>
        <div className="border-b border-gray-800">
          <Navigation />
        </div>
        {children}
        <script
          defer
          data-domain="jannismilz.com"
          src="https://analytics.aquahub.studio/js/script.js"
        ></script>
      </body>
    </html>
  );
}
