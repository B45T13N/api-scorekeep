import "../../scss/Components/Atoms/Logout.scss"
import {useAuth} from "@/app/hooks/useAuth/useAuth";
import Link from "next/link";

export const Logout = () => {

    const {logout} = useAuth();

    return (
        // eslint-disable-next-line jsx-a11y/anchor-is-valid
        <Link href="#" about="Lien de navigation pour se déconnecter" className={`navlink`} onClick={logout}>
            Se déconnecter
        </Link>
    );
};
