import React from "react";
import "../../scss/Components/Atoms/RadioInput.scss";

interface RadioInputProps {
    id: string,
    text: string,
    name: string,
    isSelected: boolean,
    onSelect: (e: React.ChangeEvent<HTMLInputElement>) => void
}

export default function RadioInput(props: RadioInputProps) {
    const handleRadioChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        props.onSelect(e);
    };

    return (
        <div data-testid={"parent-div"} className={`radio-input ${props.isSelected ? "selected" : ""}`}>
            <input
                type="radio"
                id={props.id}
                name={props.name}
                checked={props.isSelected}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) => {handleRadioChange(e)}}
            />
            <label htmlFor={props.id}>{props.text}</label>
        </div>
    );
}
