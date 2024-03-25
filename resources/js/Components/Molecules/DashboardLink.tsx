import "../../scss/Components/Molecules/DashboardLink.scss"
import Link from "next/link";

interface DashboardLinkProps {
    innerText: string,
    link: string
}
export const DashboardLink = (props: DashboardLinkProps) => {
    return (
        <Link href={props.link} about={`Lien vers ${props.innerText}`} className={"dashboard-link"}>
            <p>
                {props.innerText}
            </p>
        </Link>
    );
};
