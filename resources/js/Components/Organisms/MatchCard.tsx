import "../../scss/Components/Organisms/MatchCard.scss"
import moment from "moment";
import RegistrationVolunteerModal from "./RegistrationVolunteerModal";
import useModal from "@/hooks/useModal/useModal";

interface MatchCardProps {
    visitorTeamName: string,
    category: string,
    gameDate: Date,
    isHomeMatch: boolean,
    gameId: string
}

export default function MatchCard(props: MatchCardProps) {

    const { isOpen, toggle } = useModal();

    return (
        props.isHomeMatch ?
        <>
            <div className={"match-card"} onClick={toggle}>
                <p>{props.visitorTeamName}</p>
                <p className={"bold"}>{props.category}</p>
                {props.isHomeMatch ?
                    <p>A domicile</p>
                    :
                    <></>
                }
                <p>{moment(props.gameDate).format('DD/MM/YYYY HH:mm')}</p>
            </div>

            <RegistrationVolunteerModal isOpen={isOpen}
                                        toggle={toggle}
                                        visitorTeamName={props.visitorTeamName}
                                        gameDate={props.gameDate}
                                        gameCategory={props.category}
                                        gameId={props.gameId}
            />
        </>
            :
        <>
            <div className={"match-card no-hover"}>
                <p>{props.visitorTeamName}</p>
                <p className={"bold"}>{props.category}</p>
                {props.isHomeMatch ?
                    <p>A domicile</p>
                    :
                    <></>
                }
                <p>{moment(props.gameDate).format('DD/MM/YYYY HH:mm')}</p>
            </div>
        </>
    )
}
