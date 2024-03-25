import './teams.scss'
import TeamCard from "@/Components/Atoms/TeamCard";
import useApi from "@/hooks/useApi/useApi";
import {useEffect} from "react";
import {LocalTeam} from "@/interfaces/LocalTeam";

export default function Teams() {
    const apiUrl = `/api/local-teams`;
    const { data, error, callApi } = useApi();

    useEffect(() => {
        callApi(apiUrl);
    }, [apiUrl, callApi]);

    return (
        <article className="teams-content">
            <h2>Les équipes</h2>
            {error ? (
                <p>Erreur lors de la récupération des équipes</p>
            ) : (
                <section className={"teams-display"}>
                    {data.map((localTeam: LocalTeam) => (
                        <TeamCard key={localTeam.uuid} link={`/matchs/${localTeam.uuid}`} teamName={localTeam.name} logoPath={localTeam.logo} />
                    ))}
                </section>
            )}
        </article>
    )
}
