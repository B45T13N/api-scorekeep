import moment from 'moment';
import useApi from "@/hooks/useApi/useApi";
import apiClient from "@/services/apiClient";
import Link from "next/link";
import {Match} from "@/interfaces/Match";
import {useEffect, useState} from "react";
import './dashboardMatchs.scss';
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import {useAuth} from "@/hooks/useAuth/useAuth";
import {useRouter} from "next/router";
import {useAuthRedirect} from "@/hooks/useAuthRedirect/useAuthRedirect";

export default function DashboardMatchs() {

    const apiUrl = `/api/games`;
    const {localTeamId} = useAuth();
    const [startDate, setStartDate] = useState(moment().format('YYYY-MM-DD'));
    const [endDate, setEndDate] = useState(moment().add("2", "M").format('YYYY-MM-DD'));
    const [currentPage, setCurrentPage] = useState(1);
    const { data, meta, error, callApi } = useApi();
    const router = useRouter();

    const handlePreviousPage = () => {
        if (currentPage > 1) {
            setCurrentPage(currentPage - 1);
        }
    };

    const handleNextPage = () => {
        if (currentPage < meta.last_page) {
            setCurrentPage(currentPage + 1);
        }
    };

    const fetchMatchData = () => {
        const url = `${apiUrl}?page=${currentPage}&per_page=${meta.per_page}&local_team_id=${localTeamId}&start_date=${startDate}&end_date=${endDate}`;
        callApi(url);
    };

    useEffect(() => {
        if(localTeamId != undefined) {
            fetchMatchData();
        }
    }, [currentPage, startDate, endDate, localTeamId]);

    const handleDeleteGame = (idMatch: string) => {
        apiClient.post("/api/games/delete", {"gameId": idMatch})
            .then((response) => {
                if(response.status === 200){
                    console.log('Match deleted successfully');
                    router.reload();
                }
            })
            .catch((error) => {
                console.error(error);
            });
    }

    const handleCancelGame = (idMatch: string) => {
        apiClient.post("/api/games/cancel", {"gameId": idMatch})
            .then((response) => {
                if(response.status === 200){
                    console.log('Match cancelled successfully');
                    router.reload();
                }
            })
            .catch((error) => {
                console.error(error);
            });
    }

    const handleConfirmGame = (idMatch: string) => {
        apiClient.post("/api/games/confirm", {"gameId": idMatch})
            .then((response) => {
                if(response.status === 200){
                    console.log('Match confirmed successfully');
                    router.reload();
                }
            })
            .catch((error) => {
                console.error(error);
            });
    }

    return (
        <article className="dashboard-matchs">
            <h1>Matchs Dashboard</h1>
            <section className="filter-section">
                <div className={"date-filters"}>
                    <div className="date-filter">
                        <label htmlFor="startDate">Date de début:</label>
                        <DatePicker
                            selected={moment(startDate, 'YYYY-MM-DD').toDate()}
                            onChange={(date) => setStartDate(moment(date).format('YYYY-MM-DD'))}
                            dateFormat="yyyy-MM-dd"
                        />
                    </div>
                    <div className="date-filter">
                        <label htmlFor="endDate">Date de fin:</label>
                        <DatePicker
                            selected={moment(endDate, 'YYYY-MM-DD').toDate()}
                            onChange={(date) => setEndDate(moment(date).format('YYYY-MM-DD'))}
                            dateFormat="yyyy-MM-dd"
                        />
                    </div>
                </div>
            </section>
            <section className="content">
                <div className="add-match-button">
                    <Link href="/dashboard/matchs/add">
                        <button>Ajouter un match</button>
                    </Link>
                </div>
                {error && <h2>Erreur lors de la récupération des matchs</h2>}
                <table>
                    <thead>
                    <tr>
                        <th>Date du match</th>
                        <th className={'hidden-s'}>Contre</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    {data.map((match: Match) => (
                        <tr key={match.uuid}>
                            <td>{moment(match.gameDate).format('DD/MM/YYYY HH:mm')}</td>
                            <td className={'hidden-s'}>{match.visitorTeam.name}</td>
                            <td>{match.category}</td>
                            <td>
                                <div className={"action-buttons"}>
                                    {!match.isCancelled ? (
                                            <>
                                                <Link href={`/dashboard/matchs/edit/${match.uuid}`}>
                                                    <button>Modifier</button>
                                                </Link>
                                                <button onClick={() => {handleCancelGame(match.uuid)}}>Annuler</button>
                                                <button onClick={() => {handleDeleteGame(match.uuid)}}>Supprimer</button>
                                            </>) :
                                        (<button onClick={() => {handleConfirmGame(match.uuid)}}>Confirmer</button>)
                                    }
                                </div>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
                <div className="pagination">
                    <div className={"page-number"}>
                        Page {meta.current_page} sur {meta.last_page}
                    </div>
                    <div>
                        <button
                            onClick={handlePreviousPage} disabled={currentPage === 1}
                        >
                            Précédent
                        </button>
                        <button
                            onClick={handleNextPage} disabled={currentPage === meta.last_page}
                        >
                            Suivant
                        </button>
                    </div>
                </div>
            </section>
        </article>
    );
}

export const getServerSideProps = useAuthRedirect;