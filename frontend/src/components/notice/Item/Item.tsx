import { Link } from "react-router-dom";
import { IBoardTypes } from '../../Interfaces';
import axios from 'axios';

export const Item:React.FC<IBoardTypes> = ({id, title, reg_date, view_count, state}:IBoardTypes):JSX.Element => {
    const viewCount = () => {
        axios.post('/api/updateView.php', JSON.stringify({id}));
    }

    return(
        <tr className={state? '': 'delete'}>
            <td>{id}</td> 
            <td className="left"><Link to={''+id} onClick={viewCount}>{title}</Link></td>
            <td className="none">{reg_date?.substring(0, 10)}</td> 
            <td className="none">{view_count}</td>
        </tr>
    );
}