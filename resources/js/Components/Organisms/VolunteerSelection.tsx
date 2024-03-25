import "../../scss/Components/Organisms/VolunteerSelection.scss"
import RadioInput from "../Atoms/RadioInput";
import React, {useState} from "react";
import moment from "moment";
import apiClient from "../../../services/apiClient";
import {Volunteer} from "@/Models/Volunteer";

interface VolunteerSelectionProps {
    matchId: string,
    matchCategory: string,
    visitorTeamName: string,
    matchDate: Date,
    isHomeMatch: boolean,
    timekeepers: Array<Volunteer>,
    roomManagers: Array<Volunteer>,
    secretaries: Array<Volunteer>,
    drinkManagers: Array<Volunteer>,
    timekeeperId?: string,
    secretaryId?: string,
    roomManagerId?: string,
    drinkManagerId?: string,
}

export default function VolunteerSelection(props: VolunteerSelectionProps) {

    const [selectedTimekeeper, setSelectedTimekeeper] =
        useState(props.timekeeperId ? `${props.timekeeperId} ${props.matchId}` : "");
    const [selectedSecretary, setSelectedSecretary] =
        useState(props.secretaryId ? `${props.secretaryId} ${props.matchId}` : "");
    const [selectedRoomManager, setSelectedRoomManager] =
        useState(props.roomManagerId ? `${props.roomManagerId} ${props.matchId}` : "");
    const [selectedDrinkManager, setSelectedDrinkManager] =
        useState(props.drinkManagerId ? `${props.drinkManagerId} ${props.matchId}` : "");
    const handleRadioSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
        let id = e.target.id;
        switch (e.target.name) {
            case "secretaries":
                setSelectedSecretary(id);
                break;
            case "roomManagers":
                setSelectedRoomManager(id);
                break;
            case "timekeepers":
                setSelectedTimekeeper(id);
                break;
            case "drinkManagers":
                setSelectedDrinkManager(id);
                break;
        }
    };

    const handleClickButton = (e: React.MouseEvent<HTMLButtonElement>) => {
        let timekeeperId = selectedTimekeeper.split(" ")[0];
        let secretaryId = selectedSecretary.split(" ")[0];
        let roomManagerId = selectedRoomManager.split(" ")[0];
        let drinkManagerId = selectedDrinkManager.split(" ")[0];
        let matchId = e.currentTarget.id;

        apiClient.put(`/api/games/addVolunteers/${matchId}`, {
            'timekeeperId': timekeeperId,
            'secretaryId': secretaryId,
            'roomManagerId': roomManagerId,
            'drinkManagerId': drinkManagerId,
        }).then((response) => {
            if(response.status === 200){
                console.log('Match updated successfully');
            }
        })
            .catch((error) => {
                console.error(error);
            });
    }


    if(!props.isHomeMatch){
        return (
            <tr className={"hidden-xs"}>
                <td>
                    <div>
                        <p>{props.visitorTeamName}</p>
                        <p>{props.matchCategory}</p>
                        <p>{moment(props.matchDate).format('DD/MM/YYYY HH:mm')}</p>
                    </div>
                </td>
                <td colSpan={4}>Match à l'extérieur</td>
                <td></td>
            </tr>
        )
    }

    return (
        <tr>
            <td>
                <div>
                    <p>{props.visitorTeamName}</p>
                    <p>{props.matchCategory}</p>
                    <p className={"hidden-xs"}>{moment(props.matchDate).format('DD/MM/YYYY HH:mm')}</p>
                </div>
            </td>
            <td>
                <div className={"volunteers-grid-display"}>
                    {props.timekeepers.map((timekeeper: Volunteer) => (
                        <RadioInput
                            key={timekeeper.uuid}
                            id={`${timekeeper.uuid} ${props.matchId}`}
                            name={"timekeepers"}
                            text={timekeeper.name}
                            isSelected={selectedTimekeeper === `${timekeeper.uuid} ${props.matchId}`}
                            onSelect={(e: React.ChangeEvent<HTMLInputElement>) => {handleRadioSelect(e)}}
                        />
                    ))}
                </div>
            </td>
            <td>
                <div className={"volunteers-grid-display"}>
                    {props.secretaries.map((secretary: Volunteer) => (
                        <RadioInput
                            key={secretary.uuid}
                            id={`${secretary.uuid} ${props.matchId}`}
                            name={"secretaries"}
                            text={secretary.name}
                            isSelected={selectedSecretary === `${secretary.uuid} ${props.matchId}`}
                            onSelect={(e: React.ChangeEvent<HTMLInputElement>) => {handleRadioSelect(e)}}
                        />
                    ))}
                </div>
            </td>
            <td>
                <div className={"volunteers-grid-display"}>
                    {props.roomManagers.map((roomManager: Volunteer) => (
                        <RadioInput
                            key={roomManager.uuid}
                            id={`${roomManager.uuid} ${props.matchId}`}
                            name={"roomManagers"}
                            text={roomManager.name}
                            isSelected={selectedRoomManager === `${roomManager.uuid} ${props.matchId}`}
                            onSelect={(e: React.ChangeEvent<HTMLInputElement>) => {handleRadioSelect(e)}}
                        />
                    ))}
                </div>
            </td>
            <td>
                <div className={"volunteers-grid-display"}>
                    {props.drinkManagers.map((drinkManager: Volunteer) => (
                        <RadioInput
                            key={drinkManager.uuid}
                            id={`${drinkManager.uuid} ${props.matchId}`}
                            name={"drinkManagers"}
                            text={drinkManager.name}
                            isSelected={selectedDrinkManager === `${drinkManager.uuid} ${props.matchId}`}
                            onSelect={(e: React.ChangeEvent<HTMLInputElement>) => {handleRadioSelect(e)}}
                        />
                    ))}
                </div>
            </td>
            <td>
                <button id={props.matchId} onClick={(e) => handleClickButton(e)}>
                    Mettre à jour
                </button>
            </td>
        </tr>
    )
}
