interface ButtonProps {
    text: string
    type : "button" | "submit" | "reset" | undefined
}

export const Button = (props :ButtonProps) => {
    const className :string = props.type === "submit" ? "valid" : "error"


    return (
        <button className={className} type={props.type}>{props.text}</button>
    );
};
