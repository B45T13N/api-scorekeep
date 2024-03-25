import "../../scss/Components/Atoms/FooterLink.scss"
import Link from "next/link";

interface FooterLinkProps {
    link: string,
    innerText: string
}

export const FooterLink = (props: FooterLinkProps) => {
    return (
        <Link className={"footer-link"} href={props.link} about={`Lien vers ${props.innerText}`}>{props.innerText}</Link>
    );
};
