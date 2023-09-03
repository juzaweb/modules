import {DatatableColumn, DatatableProps} from "../../../types/datatable";
import React, {useEffect, useState} from "react";

export default function Row({table, row, checked, hasChecked, setHasChecked}: {
    table: DatatableProps,
    row: any,
    checked?: boolean,
    hasChecked: boolean,
    setHasChecked: any
}) {
    const [isChecked, setIsChecked] = useState(checked);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setIsChecked(e.target.checked);
        // if (e.target.checked) {
        //     setHasChecked(true);
        // } else {
        //     setHasChecked(false);
        // }
    }

    useEffect(() => {
        setIsChecked(checked);
    }, [checked]);

    return <tr>
        {table.actions && table.actions.length > 0 && (
            <td>
                <label className="jw__utils__control jw__utils__control__checkbox">
                    <input
                        type="checkbox"
                        className={`form-control jw-checkbox`}
                        name={'ids[]'}
                        checked={isChecked}
                        onChange={handleChange}
                    />

                    <span className="jw__utils__control__indicator"></span>
                </label>
            </td>
        )}

        {table.columns.map((column: DatatableColumn, index: number) => {
            if (table.escapes.includes(column.key)) {
                return <td key={index}>{row[column.key]}</td>;
            }

            return <td key={index} dangerouslySetInnerHTML={{__html: row[column.key]}}></td>;
        })}
    </tr>;
}