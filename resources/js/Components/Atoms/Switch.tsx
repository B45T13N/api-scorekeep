import "../../scss/Components/Atoms/Switch.scss"

interface SwitchProps {
    isOn: boolean,
    handleToggle: any,
    textOne: string,
    textTwo: string
    colorOne: string,
    colorTwo: string
}
export const Switch = (props: SwitchProps) => {
    return (
        <div className={"switch-container"}>
            <input
                checked={props.isOn}
                onChange={props.handleToggle}
                className="switch-checkbox"
                id={`switch`}
                type="checkbox"
            />
            <label
                style={{ background: props.isOn ? props.colorOne : props.colorTwo }}
                className="switch-label"
                htmlFor={`switch`}
            >
                <span className={`switch-button`} />
            </label>
            <p>
                {props.isOn ? props.textOne : props.textTwo}
            </p>
        </div>
    );
};
