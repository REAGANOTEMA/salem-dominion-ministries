// Engineering Design Components for Salem Dominion Ministries
// This file contains SVG components for engineering and architectural designs

import React from 'react';

export const CrossSectionDesign = ({ className = "w-16 h-16" }) => (
  <svg className={className} viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    {/* Background grid pattern */}
    <defs>
      <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="hsla(38, 85%, 52%, 0.1)" strokeWidth="0.5"/>
      </pattern>
      <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stopColor="hsla(41, 88%, 50%, 0.8)"/>
        <stop offset="100%" stopColor="hsla(41, 92%, 36%, 0.6)"/>
      </linearGradient>
    </defs>
    
    <rect width="100" height="100" fill="url(#grid)" opacity="0.3"/>
    
    {/* Main cross structure */}
    <g transform="translate(50, 50)">
      {/* Vertical beam */}
      <rect x="-5" y="-35" width="10" height="70" fill="url(#goldGradient)" rx="2">
        <animate attributeName="opacity" values="0.6;1;0.6" dur="4s" repeatCount="indefinite"/>
      </rect>
      
      {/* Horizontal beam */}
      <rect x="-25" y="-5" width="50" height="10" fill="url(#goldGradient)" rx="2">
        <animate attributeName="opacity" values="0.6;1;0.6" dur="4s" repeatCount="indefinite" begin="1s"/>
      </rect>
      
      {/* Decorative elements */}
      <circle cx="0" cy="-40" r="8" fill="hsla(41, 88%, 50%, 0.3)">
        <animate attributeName="r" values="6;10;6" dur="3s" repeatCount="indefinite"/>
      </circle>
      
      {/* Structural lines */}
      <g stroke="hsla(38, 85%, 52%, 0.4)" strokeWidth="1" opacity="0.6">
        <line x1="-30" y1="-30" x2="30" y2="30"/>
        <line x1="30" y1="-30" x2="-30" y2="30"/>
        <circle cx="0" cy="0" r="25" fill="none" strokeDasharray="2 2"/>
      </g>
    </g>
  </svg>
);

export const ArchitecturalPattern = ({ className = "w-24 h-24" }) => (
  <svg className={className} viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="archGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stopColor="hsla(221, 63%, 20%, 0.8)"/>
        <stop offset="100%" stopColor="hsla(221, 57%, 14%, 0.6)"/>
      </linearGradient>
      <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
        <feGaussianBlur stdDeviation="2" result="blur"/>
        <feMerge>
          <feMergeNode in="blur"/>
          <feMergeNode in="SourceGraphic"/>
        </feMerge>
      </filter>
    </defs>
    
    {/* Cathedral arch pattern */}
    <g filter="url(#glow)">
      {/* Main arch */}
      <path d="M 10 80 Q 50 20 90 80" fill="none" stroke="hsla(41, 88%, 50%, 0.6)" strokeWidth="3"/>
      
      {/* Supporting arches */}
      <path d="M 20 80 Q 50 35 80 80" fill="none" stroke="hsla(41, 88%, 50%, 0.4)" strokeWidth="2"/>
      <path d="M 30 80 Q 50 50 70 80" fill="none" stroke="hsla(41, 88%, 50%, 0.3)" strokeWidth="1.5"/>
      
      {/* Pillars */}
      <rect x="15" y="80" width="4" height="15" fill="url(#archGradient)"/>
      <rect x="81" y="80" width="4" height="15" fill="url(#archGradient)"/>
      
      {/* Decorative cross */}
      <g transform="translate(50, 50)">
        <rect x="-15" y="-2" width="30" height="4" fill="hsla(41, 88%, 50%, 0.7)"/>
        <rect x="-2" y="-15" width="4" height="30" fill="hsla(41, 88%, 50%, 0.7)"/>
      </g>
    </g>
    
    {/* Geometric pattern background */}
    <g opacity="0.2">
      <circle cx="25" cy="25" r="20" fill="none" stroke="hsla(38, 85%, 52%, 0.3)" strokeWidth="1"/>
      <circle cx="75" cy="25" r="20" fill="none" stroke="hsla(38, 85%, 52%, 0.3)" strokeWidth="1"/>
      <circle cx="50" cy="75" r="25" fill="none" stroke="hsla(38, 85%, 52%, 0.3)" strokeWidth="1"/>
    </g>
  </svg>
);

export const EngineeringDiagram = ({ className = "w-32 h-32" }) => (
  <svg className={className} viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="techGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stopColor="hsla(221, 63%, 20%, 0.9)"/>
        <stop offset="100%" stopColor="hsla(221, 57%, 14%, 0.7)"/>
      </linearGradient>
      <pattern id="techGrid" width="8" height="8" patternUnits="userSpaceOnUse">
        <rect width="8" height="8" fill="none"/>
        <path d="M 0 0 L 8 0 M 0 4 L 8 4 M 0 8 L 8 8" stroke="hsla(38, 85%, 52%, 0.1)" strokeWidth="0.5"/>
        <path d="M 0 0 L 0 8 M 4 0 L 4 8 M 8 0 L 8 8" stroke="hsla(38, 85%, 52%, 0.1)" strokeWidth="0.5"/>
      </pattern>
    </defs>
    
    {/* Background */}
    <rect width="100" height="100" fill="url(#techGrid)"/>
    
    {/* Circuit board pattern */}
    <g stroke="hsla(41, 88%, 50%, 0.6)" strokeWidth="1.5" fill="none">
      {/* Main circuit lines */}
      <path d="M 10 20 L 90 20 L 90 80 L 10 80 L 10 20" strokeDasharray="4 2"/>
      <path d="M 30 10 L 30 90" strokeDasharray="2 3"/>
      <path d="M 70 10 L 70 90" strokeDasharray="2 3"/>
      
      {/* Connection points */}
      <circle cx="30" cy="20" r="2" fill="hsla(41, 88%, 50%, 0.8)"/>
      <circle cx="70" cy="20" r="2" fill="hsla(41, 88%, 50%, 0.8)"/>
      <circle cx="30" cy="80" r="2" fill="hsla(41, 88%, 50%, 0.8)"/>
      <circle cx="70" cy="80" r="2" fill="hsla(41, 88%, 50%, 0.8)"/>
    </g>
    
    {/* Technical elements */}
    <g fill="url(#techGradient)">
      {/* Gear */}
      <g transform="translate(50, 50)">
        <circle r="15" stroke="hsla(38, 85%, 52%, 0.4)" strokeWidth="1"/>
        {/* Gear teeth */}
        {[...Array(8)].map((_, i) => (
          <rect 
            key={i}
            x="-2" 
            y="-15" 
            width="4" 
            height="5"
            transform={`rotate(${i * 45})`}
            fill="hsla(41, 88%, 50%, 0.3)"
          />
        ))}
      </g>
      
      {/* Compass */}
      <g transform="translate(25, 75)">
        <circle r="10" fill="none" stroke="hsla(38, 85%, 52%, 0.3)" strokeWidth="1"/>
        <path d="M 0 -8 L 6 0 L 0 8 L -6 0 Z" fill="hsla(41, 88%, 50%, 0.4)"/>
      </g>
      
      {/* Ruler */}
      <g transform="translate(75, 25)">
        <rect x="-10" y="-2" width="20" height="4" fill="hsla(221, 63%, 20%, 0.3)"/>
        {/* Measurement marks */}
        {[...Array(11)].map((_, i) => (
          <line 
            key={i}
            x1={-10 + i * 2} 
            y1="-4" 
            x2={-10 + i * 2} 
            y2="4"
            stroke="hsla(38, 85%, 52%, 0.4)"
            strokeWidth="0.5"
          />
        ))}
      </g>
    </g>
    
    {/* Data flow animation */}
    <g>
      <circle r="1.5" fill="hsla(41, 88%, 50%, 0.9)">
        <animateMotion dur="3s" repeatCount="indefinite">
          <mpath href="#dataPath"/>
        </animateMotion>
      </circle>
      <path id="dataPath" d="M 10 20 L 30 20 L 30 80 L 70 80 L 70 20 L 90 20" fill="none" stroke="none"/>
    </g>
  </svg>
);

export const SacredGeometry = ({ className = "w-20 h-20" }) => (
  <svg className={className} viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="sacredGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stopColor="hsla(38, 85%, 52%, 0.8)"/>
        <stop offset="100%" stopColor="hsla(41, 88%, 50%, 0.6)"/>
      </linearGradient>
      <filter id="sacredGlow" x="-50%" y="-50%" width="200%" height="200%">
        <feGaussianBlur stdDeviation="1.5" result="blur"/>
        <feMerge>
          <feMergeNode in="blur"/>
          <feMergeNode in="SourceGraphic"/>
        </feMerge>
      </filter>
    </defs>
    
    <g filter="url(#sacredGlow)">
      {/* Flower of Life pattern */}
      <g>
        {/* Central circle */}
        <circle cx="50" cy="50" r="15" fill="none" stroke="hsla(41, 88%, 50%, 0.6)" strokeWidth="2"/>
        
        {/* Surrounding circles */}
        {[...Array(6)].map((_, i) => {
          const angle = (i * Math.PI) / 3;
          const x = 50 + 15 * Math.cos(angle);
          const y = 50 + 15 * Math.sin(angle);
          return (
            <circle 
              key={i}
              cx={x} 
              cy={y} 
              r="15" 
              fill="none" 
              stroke="hsla(38, 85%, 52%, 0.3)" 
              strokeWidth="1"
            />
          );
        })}
        
        {/* Hexagram */}
        <polygon 
          points="50,20 67.3,45 32.7,45 50,70 82.3,45 17.7,45"
          fill="none"
          stroke="hsla(41, 88%, 50%, 0.4)"
          strokeWidth="1.5"
        />
        
        {/* Metatron's Cube elements */}
        <g stroke="hsla(38, 85%, 52%, 0.2)" strokeWidth="0.5">
          <line x1="50" y1="20" x2="50" y2="80"/>
          <line x1="32.7" y1="45" x2="67.3" y2="45"/>
          <line x1="17.7" y1="45" x2="82.3" y2="45"/>
          <line x1="50" y1="35" x2="50" y2="65"/>
        </g>
      </g>
      
      {/* Outer ring */}
      <circle cx="50" cy="50" r="45" fill="none" stroke="url(#sacredGradient)" strokeWidth="2">
        <animate attributeName="stroke-dasharray" values="0 300; 300 0" dur="6s" repeatCount="indefinite"/>
      </circle>
    </g>
    
    {/* Center point */}
    <circle cx="50" cy="50" r="3" fill="url(#sacredGradient)">
      <animate attributeName="r" values="2;4;2" dur="2s" repeatCount="indefinite"/>
    </circle>
  </svg>
);

export const TechnicalBlueprint = ({ className = "w-40 h-24" }) => (
  <svg className={className} viewBox="0 0 100 60" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="blueprintGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stopColor="hsla(221, 63%, 20%, 0.8)"/>
        <stop offset="100%" stopColor="hsla(221, 57%, 14%, 0.6)"/>
      </linearGradient>
      <pattern id="blueprintGrid" width="5" height="5" patternUnits="userSpaceOnUse">
        <rect width="5" height="5" fill="none"/>
        <path d="M 0 0 L 5 0 M 0 2.5 L 5 2.5 M 0 5 L 5 5" stroke="hsla(38, 85%, 52%, 0.1)" strokeWidth="0.3"/>
        <path d="M 0 0 L 0 5 M 2.5 0 L 2.5 5 M 5 0 L 5 5" stroke="hsla(38, 85%, 52%, 0.1)" strokeWidth="0.3"/>
      </pattern>
    </defs>
    
    {/* Blueprint background */}
    <rect width="100" height="60" fill="url(#blueprintGrid)" opacity="0.4"/>
    
    {/* Title block */}
    <g stroke="hsla(38, 85%, 52%, 0.3)" strokeWidth="0.5">
      <rect x="5" y="45" width="90" height="10" fill="none"/>
      <line x1="35" y1="45" x2="35" y2="55"/>
      <line x1="65" y1="45" x2="65" y2="55"/>
      <line x1="80" y1="45" x2="80" y2="55"/>
    </g>
    
    {/* Blueprint content */}
    <g fill="url(#blueprintGradient)">
      {/* Building foundation */}
      <rect x="20" y="20" width="60" height="20" rx="2" opacity="0.6"/>
      
      {/* Structural beams */}
      <rect x="25" y="15" width="50" height="3" rx="1" opacity="0.8"/>
      <rect x="25" y="37" width="50" height="3" rx="1" opacity="0.8"/>
      
      {/* Columns */}
      <rect x="25" y="20" width="3" height="20" opacity="0.7"/>
      <rect x="72" y="20" width="3" height="20" opacity="0.7"/>
      
      {/* Roof structure */}
      <polygon points="20,20 50,10 80,20" fill="none" stroke="hsla(41, 88%, 50%, 0.5)" strokeWidth="2"/>
      
      {/* Cross element */}
      <g transform="translate(50, 25)">
        <rect x="-8" y="-2" width="16" height="4" fill="hsla(41, 88%, 50%, 0.6)"/>
        <rect x="-2" y="-8" width="4" height="16" fill="hsla(41, 88%, 50%, 0.6)"/>
      </g>
    </g>
    
    {/* Dimension lines */}
    <g stroke="hsla(38, 85%, 52%, 0.4)" strokeWidth="0.5">
      <line x1="15" y1="15" x2="15" y2="40"/>
      <line x1="12" y1="15" x2="18" y2="15"/>
      <line x1="12" y1="40" x2="18" y2="40"/>
      
      <line x1="20" y1="45" x2="80" y2="45"/>
      <line x1="20" y1="42" x2="20" y2="48"/>
      <line x1="80" y1="42" x2="80" y2="48"/>
    </g>
    
    {/* Legend */}
    <g fill="hsla(41, 88%, 50%, 0.4)" fontSize="2" textAnchor="start">
      <text x="8" y="52">SCALE: 1:100</text>
      <text x="38" y="52">DRAWN BY: SDM</text>
      <text x="68" y="52">DATE: 2024</text>
      <text x="83" y="52">REV: A</text>
    </g>
  </svg>
);

export const DivineArchitecture = ({ className = "w-28 h-28" }) => (
  <svg className={className} viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="divineGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stopColor="hsla(41, 88%, 50%, 0.9)"/>
        <stop offset="100%" stopColor="hsla(38, 85%, 52%, 0.7)"/>
      </linearGradient>
      <filter id="divineGlow" x="-50%" y="-50%" width="200%" height="200%">
        <feGaussianBlur stdDeviation="3" result="blur"/>
        <feMerge>
          <feMergeNode in="blur"/>
          <feMergeNode in="SourceGraphic"/>
        </feMerge>
      </filter>
    </defs>
    
    <g filter="url(#divineGlow)">
      {/* Temple structure */}
      <g>
        {/* Base platform */}
        <rect x="20" y="70" width="60" height="10" fill="url(#divineGradient)" opacity="0.6"/>
        
        {/* Main structure */}
        <rect x="30" y="40" width="40" height="30" fill="url(#divineGradient)" opacity="0.8"/>
        
        {/* Dome */}
        <path d="M 30 40 Q 50 20 70 40" fill="url(#divineGradient)" opacity="0.7"/>
        
        {/* Pillars */}
        <rect x="32" y="40" width="3" height="30" fill="url(#divineGradient)" opacity="0.5"/>
        <rect x="65" y="40" width="3" height="30" fill="url(#divineGradient)" opacity="0.5"/>
        
        {/* Archway */}
        <path d="M 35 60 Q 50 50 65 60" fill="none" stroke="hsla(41, 88%, 50%, 0.6)" strokeWidth="2"/>
      </g>
      
      {/* Heavenly rays */}
      <g stroke="hsla(45, 95%, 70%, 0.4)" strokeWidth="1">
        <line x1="50" y1="10" x2="50" y2="30"/>
        <line x1="40" y1="15" x2="45" y2="25"/>
        <line x1="60" y1="15" x2="55" y2="25"/>
        <line x1="30" y1="20" x2="40" y2="30"/>
        <line x1="70" y1="20" x2="60" y2="30"/>
      </g>
      
      {/* Cross pinnacle */}
      <g transform="translate(50, 25)">
        <rect x="-4" y="-8" width="8" height="2" fill="hsla(41, 88%, 50%, 0.8)"/>
        <rect x="-2" y="-8" width="4" height="12" fill="hsla(41, 88%, 50%, 0.8)"/>
      </g>
    </g>
    
    {/* Ornamental border */}
    <rect x="5" y="5" width="90" height="90" fill="none" stroke="hsla(38, 85%, 52%, 0.2)" strokeWidth="1" rx="5"/>
    
    {/* Corner decorations */}
    {[{x: 10, y: 10}, {x: 90, y: 10}, {x: 10, y: 90}, {x: 90, y: 90}].map((pos, i) => (
      <circle key={i} cx={pos.x} cy={pos.y} r="2" fill="hsla(41, 88%, 50%, 0.4)"/>
    ))}
  </svg>
);

export default {
  CrossSectionDesign,
  ArchitecturalPattern,
  EngineeringDiagram,
  SacredGeometry,
  TechnicalBlueprint,
  DivineArchitecture
};