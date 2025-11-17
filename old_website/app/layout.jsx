import { Asap } from "next/font/google";
import "./globals.css";
import Navigation from "./_components/Navigation/Navigation";

const asap = Asap({
  weight: ["400", "500", "700"],
  subsets: ["latin"],
});

export const metadata = {
  openGraph: {
    type: "website",
    locale: "en_US",
    url: "https://bbwheroes.com",
    site_name: "BBW Heroes",
    description: "Where BBW students come together to form a community.",
  },
  title: "BBW Heroes",
  description: "Where BBW students come together to form a community.",
  metadataBase: process.env.APP_URL,
};

export default function RootLayout({ children }) {
  return (
    <html lang="en" className={asap.className}>
      <body>
        <div className="border-b border-gray-800">
          <Navigation />
        </div>
        {children}
      </body>
    </html>
  );
}
