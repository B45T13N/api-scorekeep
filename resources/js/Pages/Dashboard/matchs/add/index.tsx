import React, {ChangeEvent, useEffect, useState} from 'react';
import "./addMatch.scss"
import ReactDatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import moment from "moment";
import {Match} from "@/interfaces/Match";
import apiClient from "@/services/apiClient";
import {Switch} from "@/Components/Atoms/Switch";
import {Input} from "@/Components/Atoms/Input";
import Link from 'next/link';
import {useRouter} from "next/router";
import Cookies from "js-cookie";
import {useAuthRedirect} from "@/hooks/useAuthRedirect/useAuthRedirect";


export default function AddMatch() {
    const [formData, setFormData] = useState<Partial<Match>>({});
    const [visitorTeamName, setVisitorTeamName] = useState<string>("");
    const [isHomeMatch, setIsHomeMatch] = useState<boolean>(true);
    const [category, setCategory] = useState<string>("");
    const [address, setAddress] = useState<string>("");
    const [CPO, setCPO] = useState<string>("");
    const [city, setCity] = useState<string>("");
    const [errors, setErrors] = useState({ CPO});
    const [date, setDate] = useState<Date>(moment().add(1, 'd').toDate());

    const router = useRouter();

    useEffect(() => {
        const localTeamId = Cookies.get('localTeamId');

        setFormData({
                ...formData,
                localTeamId :Number(localTeamId),
                isHomeMatch: isHomeMatch,
                gameDate: date
            }
        );
    }, [date, isHomeMatch]);

    const handleAddressChange = (e: ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        setAddress(value);
        updateAddressDatas(value, CPO, city);
    };

    const handlePostalCodeChange = (e: ChangeEvent<HTMLInputElement>) => {
        const postalCode = e.target.value;
        setCPO(postalCode);
        if (/^\d+$/.test(postalCode) && postalCode.length === 5) {
            setErrors({ ...errors, CPO: "" });
        } else {
            setErrors({ ...errors, CPO: 'Code postal invalide' });
        }
        updateAddressDatas(address, postalCode, city);
    };

    const handleCityChange = (e: ChangeEvent<HTMLInputElement>) => {
        const newCity = e.target.value;
        setCity(newCity);
        updateAddressDatas(address, CPO, newCity);
    };

    const updateAddressDatas = (newAddress: string, newCPO: string, newCity: string) => {
        setFormData({
            ...formData,
            address: newAddress + "/" + newCPO + "/" + newCity
        });
    };

    const handleDatePickerChange = (newDate: Date) => {
        setFormData({
            ...formData,
            gameDate: newDate,
        });

        setDate(newDate);
    };

    const handleSwitchChange = (e: ChangeEvent<HTMLInputElement>) => {
        setIsHomeMatch(!isHomeMatch);
        setFormData({
            ...formData,
            isHomeMatch: isHomeMatch,
        });
    };

    const handleVisitorTeamNameChange = (e: ChangeEvent<HTMLInputElement>) => {
        setVisitorTeamName(e.target.value);
        setFormData({
            ...formData,
            visitorTeamName: e.target.value,
        });
    };

    const handleCategoryChange = (e: ChangeEvent<HTMLInputElement>) => {
        setCategory(e.target.value);
        setFormData({
            ...formData,
            category: e.target.value,
        });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        apiClient.post(`/api/games`, formData)
            .then((response) => {
                if(response.status === 201){
                    console.log('Match updated successfully');
                    console.log(response);
                    router.push("/dashboard/matchs");
                }
            })
            .catch((error) => {
                console.error(error);
            });
    };

    return (
        <article className={"add-match"}>
            <h2>Ajout d'un match</h2>
            <form onSubmit={handleSubmit}>
                <fieldset>
                    <legend>Détails du match : </legend>
                    <Switch
                        colorOne={"#0240b7"}
                        colorTwo={"#c2c2c2"}
                        isOn={isHomeMatch}
                        handleToggle={handleSwitchChange}
                        textOne={"A domicile"}
                        textTwo={"A l'extérieur"}
                    />
                    <div className={"datepicker"}>
                        <label htmlFor={"gameDate"}>Date du match:</label>
                        <ReactDatePicker
                            ariaLabelledBy={"gameDate"}
                            name={"gameDate"}
                            showTimeSelect
                            minDate={new Date()}
                            minTime={new Date(date.getFullYear(), date.getMonth(), date.getDate(), 8, 30)}
                            maxDate={new Date(date.getFullYear()+1, date.getMonth(), date.getDate(), 22, 0)}
                            maxTime={new Date(date.getFullYear()+1, date.getMonth(), date.getDate(), 22, 0)}
                            selected={date}
                            onChange={handleDatePickerChange}
                            dateFormat="d MMMM yyyy à HH:mm"
                            timeFormat="HH:mm"
                        />
                    </div>
                    <Input onChange={handleVisitorTeamNameChange} type={"text"} field={"Equipe adverse"} value={visitorTeamName} />
                    <Input onChange={handleCategoryChange} type={"text"} field={"Catégorie"} value={category} />
                </fieldset>
                <fieldset>
                    <legend>Adresse du gymnase : </legend>
                    <Input onChange={handleAddressChange} type={"text"} field={"Adresse"} value={address} />
                    {errors.CPO && <span className="error">{errors.CPO}</span>}
                    <Input onChange={handlePostalCodeChange} type={"text"} field={"Code postal"} maxLength={5} value={CPO}/>
                    <Input onChange={handleCityChange} type={"text"} field={"Ville"} value={city} />
                </fieldset>
                <div className={"btn-form"}>
                    <button type="submit">Ajouter le match</button>
                    <Link href={"/dashboard/matchs"}><button>Retour</button></Link>
                </div>
            </form>
        </article>
    );
}
export const getServerSideProps = useAuthRedirect;
