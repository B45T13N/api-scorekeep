import "../../scss/Components/Atoms/Select.scss"
import React, {ChangeEventHandler} from "react";
import SelectOptionsProps from "../../../interfaces/SelectOptionsProps";

interface SelectProps {
    onChange: ChangeEventHandler<HTMLSelectElement>,
    selectOptions: Array<SelectOptionsProps>
}


export default function Select(props: SelectProps) {



    return (
        <select data-testid={"select"} name="volunteer-type" id="volunteer-type" onChange={props.onChange}>
            <option value="">Sélectionner le type de poste</option>
            {props.selectOptions.map((option: any, key) => (
                <option key={key} value={option.uuid}>{option.label}</option>
            ))}
        </select>
    )
}
