import { useEffect, useState, useCallback } from "react";
import { useParams, useNavigate, Link } from "react-router-dom"
import { IFilesTypes, IBoardTypes } from '../../Interfaces';
import axios from 'axios';
import './detail.scss';

export const Detail: React.FC = (): JSX.Element => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [detail, setDetail] = useState<IBoardTypes>();
    const [files, setFiles] = useState<IFilesTypes[]>([]);

    useEffect(() => {
        axios.post('/api/selNotice.php', JSON.stringify({id}))
            .then((res: any) => {
                let resFiles: IFilesTypes[] = [];

                for (let i = 0; i < res.data[1].length; i++) {
                    resFiles[i] = {
                        id: res.data[1][i][0],
                        name: res.data[1][i][1],
                        size: res.data[1][i][2],
                        state: res.data[1][i][3]
                    }
                }

                setFiles(resFiles);

                setDetail({
                    title: res.data[0][0],
                    content: res.data[0][1],
                    reg_date: res.data[0][2],
                    state: res.data[0][3]
                });
            }).catch(e => {
                navigate('/');
            });
    }, [id, navigate]);

    const onModNotice = useCallback(() => {
        navigate('mod');
    }, [navigate]);

    const onDelNotice = useCallback(() => {
        if (window.confirm('삭제 또는 재등록 하시겠습니까?')) {
            axios.post('/api/delNotice.php', JSON.stringify({id}))
            .then((res: any) => {
                setDetail({ ...detail, state: !(detail?.state) });
            });
        }
    }, [detail, id]);

    const formatByte = (bytes: number, decimals = 2) => {
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    const fileReq = useCallback((id) => {
        axios.post('/api/fileDownload.php', JSON.stringify({id}), {
            responseType: 'blob',
        })
        .then(res => {
            const fileName = res.headers['content-disposition'].substring(21);
            const url = window.URL.createObjectURL(new Blob([res.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', fileName);
            document.body.appendChild(link);
            link.click();
        });
    }, []);

    return (
        <div className="noticeDetail">
            <div className="noticeDetail-title">
                <span>{detail?.title}</span>
                <span>{detail?.reg_date?.replace('T', ' ').substring(0, 16)}</span>
            </div>

            <div className="noticeDetail-content">
                <div className="noticeDetail-content-text">
                    <div dangerouslySetInnerHTML={{ __html: detail?.content as string }} />
                </div>
                <div className="noticeDetail-content-attachArea">첨부파일</div>
                <div className="noticeDetail-content-attach">
                    {                
                    files.sort((a:IFilesTypes, b:IFilesTypes): number => {
                        return a.state - b.state;
                    }).map((file) => {
                            return (
                            <div key={file.id} className={`noticeDetail-content-attach-info ${file.state === 2 || file.state === 0? 'strike':''}`}>
                                <span onClick={() => fileReq(file.id)}>{file.name}</span>
                                <span>{formatByte(parseInt(file.size))}</span>
                            </div>
                            )
                        }
                    )
                    }
                </div>
            </div>

            <div className="noticeDetail-btn">
            {
                detail?.state ? (
                    <>
                        <span className="btn" onClick={onModNotice}>수정</span>
                        <span className="btn" onClick={onDelNotice}>삭제</span>
                    </>
                ) : (
                    <>
                        <span className="btn" onClick={onModNotice}>수정</span>
                        <span className="btn" onClick={onDelNotice}>재등록</span>
                    </>
                )
            }
            </div>
        </div>
    );
}