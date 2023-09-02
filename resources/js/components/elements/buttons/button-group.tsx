import ElementBuilderChildren from "../../ElementBuilderChildren";

export interface ButtonGroupProps {
    className: string;
    id?: string;
    children?: Array<any>
}

export default function ButtonGroup({ className, id, children }: ButtonGroupProps) {
    return (
        <div className={className} id={id}>
            <ElementBuilderChildren children={children} />
        </div>
    );
};