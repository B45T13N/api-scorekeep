import './dashboard.scss'
import {DashboardLink} from "@/Components/Molecules/DashboardLink";
import {NextPage} from "next";
import {useAuthRedirect} from "@/hooks/useAuthRedirect/useAuthRedirect";

interface DashboardLinks {
    innerText: string,
    link: string
}

const Dashboard: NextPage = () => {
    const dashboardLinks :Array<DashboardLinks> = [
        {innerText: "Gestion des matchs",link: "/dashboard/matchs"},
        {innerText: "Gestion des bénévoles",link: "/dashboard/volunteers"},
    ];

    return (
        <section className="dashboard">
            <div>
                <h2>Panneau d'administration</h2>
            </div>
            <div className="dashboard-links">
                {dashboardLinks.map((obj, key) =>
                    <DashboardLink key={key} link={obj.link} innerText={obj.innerText}/>
                )}
            </div>
        </section>
    )
}
export const getServerSideProps = useAuthRedirect;

export default Dashboard;
