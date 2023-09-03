import {__} from "../../../helpers/functions";
import {DatatableAction, DatatableProps} from "../../../types/datatable";
import axios from "axios";

export default function BulkActions({actions, actionUrl}: DatatableProps) {
    const handleBulkAction = (e: any, action: DatatableAction) => {
        axios.post(actionUrl, {
            action: action.key,
            ids: actions.map((action: DatatableAction) => {

            })
        }).then((res) => {

        });
    }

    return (
        actions.length > 0 && (
            <div className="col-md-2">
                <form method="post" className="form-inline">
                    <div className="dropdown d-inline-block mb-2 mr-2">
                        <button
                            type="button"
                            className="btn btn-primary dropdown-toggle bulk-actions-button"
                            data-toggle="dropdown"
                            aria-expanded="false"
                            disabled={true}
                        >
                            {__('cms::app.bulk_actions')}
                        </button>

                        <div
                            className="dropdown-menu bulk-actions-actions"
                            role="menu"
                            x-placement="bottom-start"
                        >
                            {actions.map((action: DatatableAction, index: number) => (
                                <a
                                    className={`dropdown-item select-action action-${action.key}` + (action.key == 'delete' ? ' text-danger' : '')}
                                    href="#"
                                    data-action={action.key}
                                    key={index}
                                    onClick={e => handleBulkAction(e, action)}
                                >
                                    {action.label}
                                </a>
                            ))}
                        </div>
                    </div>
                </form>
            </div>
        )
    );
}
