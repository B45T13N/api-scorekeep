import '../../scss/Components/Molecules/Navbar.scss'
import {Navlink} from "../Atoms/Navlink";
import {Logout} from "../Atoms/Logout";
import {NavlinkObject} from "@/Models/NavlinkObject";
import { User } from '@/types';

export default function Navbar() {

    const navlinks :Array<NavlinkObject> = [
        {innerText: "Accueil",link: "/"},
        {innerText: "Les équipes",link: "/teams"},
    ];

    if(!isAuthenticated){
        navlinks.push(
            {innerText: "Se connecter",link: "/login"}
        )
    }

    return (
         <nav data-testid={"navbar"}>
             <ul className={"link-list"}>
                 {navlinks.map((obj, key) =>
                    <li key={key}><Navlink link={obj.link} innerText={obj.innerText} isActive={url === obj.link}/></li>
                 )}
                 {isAuthenticated &&
                     (<>
                         <li>
                             <Navlink link={"/dashboard"} innerText={"Dashboard"} isActive={url === "/dashboard"}/>
                         </li>
                         <li>
                             <Logout />
                         </li>
                     </>)
                 }
             </ul>
         </nav>
    )
}
