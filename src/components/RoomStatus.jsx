import React, { useState, useEffect } from 'react';

const RoomStatus = ({ apiEndpoint = '/api/room-status.php' }) => {
  const [roomData, setRoomData] = useState(null);
  const [loading, setLoading] = useState(true);

  const fetchRoomStatus = async () => {
    try {
      const response = await fetch(apiEndpoint);
      const data = await response.json();
      setRoomData(data);
      setLoading(false);
    } catch (error) {
      console.error('Error:', error);
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchRoomStatus();
    const interval = setInterval(fetchRoomStatus, 3000);
    return () => clearInterval(interval);
  }, []);

  const handleFinish = async () => {
    if (!roomData?.current?.id) return;
    
    try {
      const response = await fetch('/api/finish-service.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ member_id: roomData.current.id })
      });
      
      if (response.ok) {
        fetchRoomStatus();
      }
    } catch (error) {
      console.error('Error finishing service:', error);
    }
  };

  const handleCallNext = async () => {
    if (!roomData?.next?.id) return;
    
    try {
      const response = await fetch('/api/enter-room.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ member_id: roomData.next.id })
      });
      
      if (response.ok) {
        fetchRoomStatus();
      }
    } catch (error) {
      console.error('Error calling next:', error);
    }
  };

  if (loading) {
    return <div>Loading room status...</div>;
  }

  const isOccupied = roomData?.current !== null;

  return (
    <div style={{
      backgroundColor: 'white',
      borderRadius: '12px',
      padding: '24px',
      boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
      border: `3px solid ${isOccupied ? '#ef4444' : '#10b981'}`
    }}>
      <div style={{
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        marginBottom: '20px'
      }}>
        <h2 style={{ margin: 0, fontSize: '24px', color: '#1f2937' }}>
          🏥 {roomData?.room?.room_name || 'Service Room'}
        </h2>
        <div style={{
          padding: '8px 16px',
          borderRadius: '20px',
          backgroundColor: isOccupied ? '#fee2e2' : '#dcfce7',
          color: isOccupied ? '#991b1b' : '#166534',
          fontWeight: '600',
          fontSize: '14px'
        }}>
          {isOccupied ? '🔴 OCCUPIED' : '🟢 AVAILABLE'}
        </div>
      </div>

      {isOccupied ? (
        <div>
          <div style={{
            backgroundColor: '#fef3c7',
            padding: '16px',
            borderRadius: '8px',
            marginBottom: '16px'
          }}>
            <h3 style={{ margin: '0 0 8px', fontSize: '16px', color: '#92400e' }}>
              Currently Serving:
            </h3>
            <div style={{ fontSize: '20px', fontWeight: '600', color: '#1f2937' }}>
              #{roomData.current.queue_number} - {roomData.current.name}
            </div>
            <div style={{ fontSize: '14px', color: '#6b7280', marginTop: '8px' }}>
              Entered at: {new Date(roomData.current.entered_room_at).toLocaleTimeString()}
            </div>
          </div>
          
          <button onClick={handleFinish} style={{
            width: '100%',
            padding: '12px',
            backgroundColor: '#3b82f6',
            color: 'white',
            border: 'none',
            borderRadius: '8px',
            fontSize: '16px',
            fontWeight: '600',
            cursor: 'pointer'
          }}>
            ✅ Finish Service
          </button>
        </div>
      ) : (
        <div>
          {roomData?.next ? (
            <div>
              <div style={{
                backgroundColor: '#eff6ff',
                padding: '16px',
                borderRadius: '8px',
                marginBottom: '16px'
              }}>
                <h3 style={{ margin: '0 0 8px', fontSize: '16px', color: '#1e40af' }}>
                  Next in Queue:
                </h3>
                <div style={{ fontSize: '18px', fontWeight: '600', color: '#1f2937' }}>
                  #{roomData.next.queue_number} - {roomData.next.name}
                </div>
              </div>
              
              <button onClick={handleCallNext} style={{
                width: '100%',
                padding: '12px',
                backgroundColor: '#10b981',
                color: 'white',
                border: 'none',
                borderRadius: '8px',
                fontSize: '16px',
                fontWeight: '600',
                cursor: 'pointer'
              }}>
                📢 Call Next Person
              </button>
            </div>
          ) : (
            <p style={{ textAlign: 'center', color: '#6b7280', padding: '20px' }}>
              No one waiting in queue
            </p>
          )}
        </div>
      )}
      
      {roomData?.waiting_count > 0 && (
        <div style={{
          marginTop: '16px',
          padding: '12px',
          backgroundColor: '#f3f4f6',
          borderRadius: '8px',
          textAlign: 'center'
        }}>
          <span style={{ fontSize: '14px', color: '#6b7280' }}>People waiting: </span>
          <span style={{ fontSize: '16px', fontWeight: '600', color: '#1f2937' }}>
            {roomData.waiting_count}
          </span>
        </div>
      )}
    </div>
  );
};

export default RoomStatus;
