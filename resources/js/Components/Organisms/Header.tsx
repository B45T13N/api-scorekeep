import '../../scss/Components/Organisms/Header.scss'
import Navbar from "../Molecules/Navbar";
import Link from "next/link";
import {useEffect, useState} from "react";

export default function Header() {
    const [scrolling, setScrolling] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            if (window.scrollY > 0) {
                setScrolling(true);
            } else {
                setScrolling(false);
            }
        };

        window.addEventListener('scroll', handleScroll);

        return () => {
            window.removeEventListener('scroll', handleScroll);
        };
    }, []);

    return (
        <header className={`app-header ${scrolling ? 'app-header--scrolling' : ''}`}>
            <div className={"app-title"}>
                <h1>Scorekeep</h1>
            </div>
            <div className="logo-app hidden-xs">
                <Link href="/">
                    <img src={"/logo192.png"} alt={"Logo de l'application Scorekeep"} width={80} height={80} />
                </Link>
            </div>
            <div className={`links ${scrolling ? 'links--scrolling' : ''}`}>
                <Navbar />
            </div>
        </header>
    )
}
