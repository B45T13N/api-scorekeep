import "../../scss/Components/Organisms/Footer.scss"
// import {FooterLink} from "../../Atoms/FooterLink/FooterLink";
//
// interface FooterLinkProps {
//     link: string,
//     innerText: string
// }

export default function Footer () {
    // const footerLinks :Array<FooterLinkProps> = [
    //     {link: "mailto:contact@scorekeep.org", innerText: "Nous contacter"},
    //     {link: "/cgu", innerText: "Conditions générales d'utilisations"}
    // ]

    return (
        <footer>
            {/*<div className={"footer-link"}>*/}
            {/*    {footerLinks.map((obj, key) =>*/}
            {/*        <FooterLink link={obj.link} innerText={obj.innerText} key={key}/>*/}
            {/*    )}*/}
            {/*</div>*/}
            <div className={"copyright"}>
                <h5>Tous droits réservés - &copy; DoWeDev</h5>
            </div>
        </footer>
    );
};
