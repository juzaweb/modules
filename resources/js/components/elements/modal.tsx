import ElementBuilderChildren from "../ElementBuilderChildren";

export interface ModalProps {
    title?: string;
    id?: string;
    children?: Array<any>;
    footerChildren?: Array<any>;
}

export default function Modal(props: ModalProps) {
    return <div
        className={`modal fade`}
        id={props.id}
        tabIndex={-1}
        role="dialog"
        aria-labelledby={`${props.id}-label`}
        aria-hidden="true"
    >
        <div className="modal-dialog" role="document">
            <div className="modal-content">
                {props.title && (
                    <div className="modal-header">
                        <h5 className="modal-title" id={`${props.id}-label`}>{props.title}</h5>
                        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                )}

                <div className="modal-body">
                    <ElementBuilderChildren children={props.children}/>
                </div>

                {props.footerChildren && (
                    <div className="modal-footer">
                        <ElementBuilderChildren children={props.footerChildren}/>
                    </div>
                )}
            </div>
        </div>
    </div>;
}
