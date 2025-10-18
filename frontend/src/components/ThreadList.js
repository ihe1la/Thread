import React, { useState, useEffect } from 'react';
import { getThreads } from '../services/api';

function ThreadList() {
  const [threads, setThreads] = useState([]);

  useEffect(() => {
    const fetchThreads = async () => {
      try {
        const response = await getThreads();
        setThreads(response.data);
      } catch (err) {
        console.error('Error fetching threads:', err);
      }
    };

    fetchThreads();
  }, []);

  return (
    <div>
      <h2>Threads</h2>
      <div className="list-group">
        {threads.map(thread => (
          <div key={thread.id} className="list-group-item">
            <h5 className="mb-1">{thread.title}</h5>
            <div className="mb-1" dangerouslySetInnerHTML={{ __html: thread.content }} />
            <small>Posted by {thread.username}</small>
            {thread.image_path && (
              <div className="mt-2">
                <img src={`${process.env.REACT_APP_API_URL}/uploads/${thread.image_path}`} 
                     alt="Thread attachment" 
                     className="img-fluid" />
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}

export default ThreadList;