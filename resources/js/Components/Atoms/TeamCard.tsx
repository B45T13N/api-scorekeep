import "../../scss/Components/Atoms/TeamCard.scss"
import Link from "next/link";

interface TeamCardProps {
    teamName: string,
    logoPath: string,
    link: string
}

export default function TeamCard(props: TeamCardProps) {
    return (
        <Link href={props.link}>
            <div className={"team-card"}>
                <img src={props.logoPath} alt="Logo de l'équipe" height={80} width={80}/>
                <p>{props.teamName}</p>
            </div>
        </Link>
    )
}
