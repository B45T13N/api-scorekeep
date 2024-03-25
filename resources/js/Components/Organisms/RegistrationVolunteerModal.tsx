import "../../scss/Components/Organisms/RegistrationVolunteerModal.scss"
import React, {ChangeEvent, useEffect, useState} from "react";
import apiClient from "../../../services/apiClient";
import {Input} from "../Atoms/Input";
import moment from "moment";
import Select from "../Atoms/Select";
import SelectOptionsProps from "../../../interfaces/SelectOptionsProps";
import Image from "next/image";
import Close from '@/images/x.svg';

interface RegistrationVolunteerModalProps {
    isOpen: boolean;
    toggle: () => void;
    gameDate: Date,
    visitorTeamName: string,
    gameCategory: string,
    gameId: string
}

export default function RegistrationVolunteerModal(props: RegistrationVolunteerModalProps) {
    const APIUri = "/api/volunteers/store";
    const [name, setName] = useState<string>("");
    const [password, setPassword] = useState<string>("");
    const [volunteerTypeId, setVolunteerTypeId] = useState<string>("");
    const [error, setError] = useState<string>("");
    const [selectOptions, setSelectOptions] = useState<Array<SelectOptionsProps>>(new Array<SelectOptionsProps>())

    useEffect(() => {
        apiClient.get('/api/volunteer-types/show-all')
            .then(result => {
                setSelectOptions(result.data);
            })
            .catch(error => {
                console.log(error);
            })
    }, []);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        apiClient.post(`${APIUri}`,
            {
                "name": name,
                "gameId": props.gameId,
                "token": password,
                "volunteerTypeId": volunteerTypeId
            }
            )
            .then((response) => {
                if(response.status === 201){
                    console.log('Registration done');
                    handleClose();
                }
            })
            .catch((error) => {
                console.error(error);
                setError("Erreur lors de l'enregistrement.");
            });
    };

    const handleVisitorNameChange = (e: ChangeEvent<HTMLInputElement>) => {
        setName(e.target.value);
    };

    const handlePasswordChange = (e: ChangeEvent<HTMLInputElement>) => {
        setPassword(e.target.value);
    };

    const handleSelectChange = (e: ChangeEvent<HTMLSelectElement>) => {
        setVolunteerTypeId(e.target.value);
    };

    const handleClose = () => {
        props.toggle()
        setError("");
    }

    return (
        <>
            {props.isOpen && (
                <div className={"dark-bg"} onClick={handleClose}>
                    <div className={"centered"}>
                        <div className={"modal"} onClick={(e) => e.stopPropagation()}>
                            <div className={"modal-header"}>
                                <h5 className={"heading"}>{props.gameCategory} contre {props.visitorTeamName}</h5>
                                <p>{moment(props.gameDate).format('DD/MM/YYYY HH:mm')}</p>
                            </div>
                            <div className={"close-btn"} onClick={handleClose}>
                                <Image
                                    alt={"Fermer la fenêtre"}
                                    src={Close}
                                    height={32}
                                    width={32}
                                    />
                            </div>
                            <div className={"error"}>
                                {error && (
                                    <div className={"modal-content"}>
                                        <h5>{error}</h5>
                                    </div>
                                )}
                                <form className={"form-submit"} onSubmit={handleSubmit}>
                                    <Select onChange={handleSelectChange} selectOptions={selectOptions}/>
                                    <Input type={"text"} maxLength={30} field={"nom"} onChange={handleVisitorNameChange} />
                                    <Input type={"password"} maxLength={30} field={"mot de passe"} onChange={handlePasswordChange} />

                                    <div className={"modal-actions"}>
                                        <div className={"actions-container"}>
                                            <button className={"confirm-btn"} onClick={handleSubmit} type={"submit"}>
                                                S'enregistrer
                                            </button>
                                            <button
                                                className={"cancel-btn"}
                                                onClick={handleClose}
                                            >
                                                Annuler
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    )
}
