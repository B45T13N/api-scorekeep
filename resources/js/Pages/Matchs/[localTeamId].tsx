import './matchs.scss'
import React, {useEffect, useState} from "react";
import apiClient from "@/services/apiClient";
import {Match} from "@/interfaces/Match";
import MatchCard from "@/Components/Organisms/MatchCard";
import {useRouter} from "next/router";

export default function Matchs() {
    const router = useRouter();
    const { localTeamId } = router.query;

    const apiUrl = `/api/weekGames?local_team_id=${localTeamId}`;
    const [error, setError] = useState<boolean>(false);
    const [data, setData] = useState([]);


    useEffect(() => {
        if(localTeamId != undefined)
        {
            apiClient.get(apiUrl)
                .then((result) =>{
                    setData(result.data.data)
                })
                .catch(() => {
                    setError(true);
                    console.log("Erreur lors de la récupération des matchs");
                })
        }
    }, [apiUrl, localTeamId]);



    return (
        <article className="matchs-content">
            <h2>Les matchs</h2>
            {data.length === 0 ? (
                    error ?
                        <p>Erreur lors de la récupération des données</p>
                        :
                        <p>Pas de matchs disponible.</p>
                )
                : (
                    <section className={"matchs-display"}>
                        {data.map((match: Match) => (
                            <MatchCard
                                key={match.uuid}
                                visitorTeamName={match.visitorTeam.name}
                                category={match.category}
                                gameDate={match.gameDate}
                                isHomeMatch={match.isHomeMatch}
                                gameId={match.uuid.toString()}
                            />
                        ))}
                    </section>
                )}
        </article>
    )
}
